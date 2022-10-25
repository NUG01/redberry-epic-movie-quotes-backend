<?php

namespace App\Http\Controllers;

use App\Mail\RegisterEmail;
use Illuminate\Support\Facades\Mail;

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
}
