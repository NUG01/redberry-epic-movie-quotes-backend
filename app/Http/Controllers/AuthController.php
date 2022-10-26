<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
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

	public function login(LoginRequest $request)
	{
		$username = $request->validated()['name'];
		$password = $request->validated()['password'];
		$this->validated = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

		if (auth()->attempt([$this->validated=>$username, 'password'=>$password, 'is_verified' => 1], $request->has('remember_device')))
		{
			$token = auth()->attempt($request->all());
		}

		if (!$token)
		{
			return response()->json(['error' => 'User Does not exist!'], 404);
		}

		return response()->json([
			'access_token'=> $token,
			'token_type'  => 'bearer',
			'expired_in'  => auth()->factory()->getTTL() * 60,
		]);
	}
}
