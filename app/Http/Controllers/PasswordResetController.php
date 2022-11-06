<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
	public function submitForgetPasswordForm(ResetPasswordRequest $request)
	{
		$token = bin2hex(random_bytes(32));
		DB::table('password_resets')->insert([
			'email'     => $request->email,
			'token'     => $token,
			'created_at'=> Carbon::now(),
		]);

		$url = env('FRONTEND_URL') . '/landing/recover-password/' . $token . '?email=' . $request->email;
		$body = 'Forgot password? No worries, you can recover it easily.';
		$buttonText = 'Recover password';
		Mail::send('emails.reset', ['url'=>$url, 'body'=>$body, 'buttonText'=>$buttonText], function ($message) use ($request) {
			$message->from(env('MAIL_USERNAME'), 'Epic Movie Quotes');
			$message->to($request->email, 'Epic Movie Quotes')->subject('Reset Password');
		});
		return response()->json('Email sent!', 200);
	}

	public function submitResetPasswordForm(RecoverPasswordRequest $request)
	{
		$checkToken = DB::table('password_resets')->where([
			'token'=> $request->token['id'],
		])->first();
		if (!$checkToken)
		{
			return response()->json('Password can not be recovered!', 401);
		}
		elseif ($checkToken)
		{
			User::where('email', $checkToken->email)->update([
				'password'=> Hash::make($request->password),
			]);

			DB::table('password_resets')->where([
				'email'=> $checkToken->email,
			])->delete();
			return response()->json('Password recovered successfully!', 200);
		}
	}
}
