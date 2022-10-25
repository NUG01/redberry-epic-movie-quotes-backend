<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
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

	public function verifyUser(Request $request)
	{
		$verificationCode = $request->code;
		$user = User::where(['verification_code'=>$verificationCode])->first();
		if ($user != null)
		{
			$user->is_verified = 1;
			$user->save();
			return redirect('http://localhost:5173/landing');
		}
		else
		{
			return response()->json('User can not be verified!', 401);
		}
	}
}
