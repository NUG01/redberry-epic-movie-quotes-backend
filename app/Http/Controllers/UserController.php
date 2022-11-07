<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGoogleProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function userData()
	{
		return response()->json(auth()->user(), 200);
	}

	public function update(UpdateProfileRequest $request)
	{

		$data = $request->validated();
		$email = auth()->user()->email;
		$currentThumbnail = auth()->user()->thumbnail;

		if($request->password){

			$data['password'] = bcrypt($data['password']);
		}

		$token = bin2hex(random_bytes(32));

		if ($request->email != $email && $request->email)
		{
			$oldEmailChanges = DB::table('email_changes')->where('email', $email);

			if ($oldEmailChanges)
			{
				$oldEmailChanges->delete();
			}

			DB::table('email_changes')->insert([
				'email'         => $email,
				'new_email'     => $request->email,
				'token'         => $token,
				'created_at'    => Carbon::now(),
			]);

			$url = env('FRONTEND_URL') . '/update-email/' . $token . '?email=' . $email;
			$body = 'You asked for Email change? then change it.';
			$buttonText = 'Change email';
			Mail::send('emails.reset', ['url'=>$url, 'body'=>$body, 'buttonText'=>$buttonText], function ($message) use ($request) {
				$message->from(env('MAIL_USERNAME'), 'Epic Movie Quotes');
				$message->to($request->email, 'Epic Movie Quotes')->subject('Change Email');
			});
		}
		$data['email'] = $email;

		if ($currentThumbnail && $currentThumbnail != 'assets/LaracastImage.png' && $request->thumbnail)
		{
			$absolutePath = public_path($currentThumbnail);
			File::delete($absolutePath);
		}

		if ($request->thumbnail)
		{
			$relativePath = $this->saveImage($data['thumbnail']);
			$data['thumbnail'] = $relativePath;
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

		// User::where('id', auth()->user()->id)->update([
		// 	'email'=> $request->email,
		// ]);
		// 	return response()->json('Email changed successfully!', 200);
		$checkToken = DB::table('email_changes')->where([
			'token'=> $request->token,
		])->first();
		if (!$checkToken)
		{
			return response()->json('Email can not be changed!', 401);
		}
		elseif ($checkToken)
		{
			$newEmail = DB::table('email_changes')->where('token', $checkToken->token)->first();
			User::where('email', $checkToken->email)->update([
				'email'=> $newEmail->new_email,
			]);

			DB::table('email_changes')->where([
				'email'=> $checkToken->email,
			])->delete();
			return response()->json('Email changed successfully!', 200);
		}
	}

	private function saveImage($image)
	{
		if (preg_match('/^data:image\/(\w+);base64,/', $image, $type))
		{
			$image = substr($image, strpos($image, ',') + 1);
			$type = strtolower($type[1]);

			if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png']))
			{
				throw new \Exception('invalid image type');
			}
			$image = str_replace(' ', '+', $image);
			$image = base64_decode($image);
		}

		$dir = 'images/';
		$file = Str::random(12) . '.' . $type;
		$absolutePath = public_path($dir);
		$relativePath = $dir . $file;
		if (!File::exists($absolutePath))
		{
			File::makeDirectory($absolutePath, 0755, true);
		}
		file_put_contents($relativePath, $image);

		return $relativePath;
	}
}
