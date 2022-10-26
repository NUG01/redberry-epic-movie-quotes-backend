<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function register(RegisterRequest $request)
	{
		$user = User::create([
			'name'              => $request->name,
			'email'             => $request->email,
			'password'          => Hash::make($request->password),
			'verification_code' => sha1(time()),
		]);

		if ($user != null)
		{
			EmailVerificationController::sendEmail($user->name, $user->email, $user->verification_code);
		}
		return response()->json('Registration is successful!', 200);
	}
}
