<?php

namespace App\Http\Controllers;

use App\Mail\RegisterEmail;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
	public static function sendEMail($name, $email, $verificationCode, $subject, $views)
	{
		$data = [
			'email'             => $email,
			'verification_code' => $verificationCode,
			'name'              => $name,
			'subject'           => $subject,
			'views'             => $views,
		];
		Mail::to($email)->send(new RegisterEmail($data));
	}

	public static function sendVerifyEMail($email, $code, $subject, $views, $body, $buttonText, $url)
	{
		$data = [
			'email'             => $email,
			'verification_code' => $code,
			'subject'           => $subject,
			'views'             => $views,
			'body'              => $body,
			'buttonText'        => $buttonText,
			'verification_code' => $url,
		];
		Mail::to($email)->send(new VerifyEmail($data));
	}

	public function verifyUser(Request $request)
	{
		$verificationCode = $request->code;
		$user = User::where(['verification_code'=>$verificationCode])->first();
		if ($user != null)
		{
			$user->is_verified = 1;
			$user->save();
			return redirect(env('FRONTEND_URL') . '/landing/email-verified');
		}
		else
		{
			return response()->json('User can not be verified!', 401);
		}
	}
}
