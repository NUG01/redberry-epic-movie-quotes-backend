<?php

namespace App\Http\Controllers;

use App\Mail\RegisterEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
	public static function sendEMail($name, $email, $verificationCode)
	{
		$data = [
			'email'             => $email,
			'verification_code' => $verificationCode,
			'name'              => $name,
		];
		Mail::to($email)->send(new RegisterEmail($data));
	}

	public function verifyUser(Request $request)
	{
		$verificationCode = $request->code;
		$user = User::where(['verification_code'=>$verificationCode])->first();
		if ($user != null)
		{
			$user->email_verified_at = Carbon::now();
			$user->save();
			return redirect(env('FRONTEND_URL') . '/landing/email-verified');
		}
		else
		{
			return response()->json('User can not be verified!', 401);
		}
	}
}
