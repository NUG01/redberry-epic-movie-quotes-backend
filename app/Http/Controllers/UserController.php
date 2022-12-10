<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewEmailRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function index(): JsonResponse
	{
		$user = jwtUser();
		if ($user)
		{
			$userData = [
				'id'       => $user->id,
				'name'     => $user->name,
				'email'    => $user->email,
				'thumbnail'=> $user->thumbnail,
				'google_id'=> $user->google_id,
				'emails'   => $user->emails,
			];
		}
		else
		{
			$userData = null;
		}
		return response()->json(['user' => $userData]);
	}

	public function update(UpdateProfileRequest $request): JsonResponse
	{
		$data = $request->validated();
		$email = jwtUser()->email;
		$currentThumbnail = jwtUser()->thumbnail;
		$thumbnail = $request->file('thumbnail')->store('images');

		if ($request->password)
		{
			$data['password'] = bcrypt($data['password']);
		}

		$code = jwtUser()->verification_code;

		if (($request->email != $email) && $request->email)
		{
			$url = env('FRONTEND_URL_FOR_CONFIRM') . '/update-email/' . $code . '?email=' . $request->email;
			$body = 'You asked for Email change? then change it.';
			$buttonText = 'Change email';
			EmailVerificationController::sendVerifyEmail($request->email, $code, 'Change Email', 'emails.reset', $body, $buttonText, $url);
			$data['is_verified'] = 0;
		}
		$data['email'] = $email;
		if ($currentThumbnail && $currentThumbnail != 'assets/LaracastImage.png' && $thumbnail)
		{
			$absolutePath = storage_path('/app/' . $currentThumbnail);
			File::delete($absolutePath);
		}

		if ($thumbnail)
		{
			$data['thumbnail'] = $thumbnail;
		}
		else
		{
			$data['thumbnail'] = $currentThumbnail;
		}
		User::where('email', $email)->update($data);
		return response()->json($data);
	}

	public function submitChangeEmail(Request $request): JsonResponse
	{
		if ($request->email)
		{
			DB::table('users')->where('verification_code', $request->token)->update(['email'=>$request->email, 'is_verified'=>1]);
		}
		return response()->json('Email changed successfully!');
	}

	public function addNewEmail(NewEmailRequest $request): JsonResponse
	{
		Email::create([
			'user_id'=> jwtUser()->id,
			'address'=> $request->new_email,
		]);
		$user = jwtUser();
		EmailVerificationController::sendEmail($user->name, $request->new_email, $request->new_email, 'Account Confirmation', 'emails.register');
		return response()->json(Email::where('user_id', jwtUser()->id)->get());
	}

	public function destroy(Email $email): JsonResponse
	{
		$email->delete();
		return response()->json(Email::where('user_id', jwtUser()->id)->get());
	}
}
