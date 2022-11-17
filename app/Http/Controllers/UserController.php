<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class UserController extends Controller
{

	public function index()
	{
		return response()->json(User::get(), 200);
	}

	public function getGoogleUser($id)
	{
		return response()->json(User::where('id', $id)->first(), 200);
	}

	
	public function userData()
	{
		return response()->json(auth()->user(), 200);
	}

	public function update(UpdateProfileRequest $request)
	{
		$data = $request->validated();
		$email = auth()->user()->email;
		$currentThumbnail = auth()->user()->thumbnail;
		$thumbnail = $request->file('thumbnail')->store('images');

		if ($request->password)
		{
			$data['password'] = bcrypt($data['password']);
		}

		$code = auth()->user()->verification_code;

		if ($request->email != $email && $request->email)
		{
			$url = env('FRONTEND_URL') . '/update-email/' . $code . '?email=' . $request->email;
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
		return response()->json($data, 200);
	}

	public function submitChangeEmail(Request $request)
	{
		if ($request->email)
		{
			DB::table('users')->where('verification_code', $request->token)->update(['email'=>$request->email, 'is_verified'=>1]);
		}
		return response()->json('Email changed successfully!', 200);
	}
}
