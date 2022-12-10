<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
	public function submitForgetPasswordForm(ResetPasswordRequest $request): JsonResponse
	{
		$code = bin2hex(random_bytes(32));
		DB::table('password_resets')->insert([
			'email'     => $request->email,
			'token'     => $code,
			'created_at'=> Carbon::now(),
		]);

		$url = env('FRONTEND_URL_FOR_CONFIRM') . '/landing/recover-password/' . $code . '?email=' . $request->email;
		$body = 'Forgot password? No worries, you can recover it easily.';
		$buttonText = 'Recover password';
		EmailVerificationController::sendVerifyEMail($request->email, $code, 'Reset Password', 'emails.reset', $body, $buttonText, $url);
		return response()->json('Email sent!');
	}

	public function submitResetPasswordForm(RecoverPasswordRequest $request): JsonResponse
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
			return response()->json('Password recovered successfully!');
		}
	}
}
