<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function index()
	{
		return response()->json(User::get(), 200);
	}

	public function getGoogleUser($id)
	{
		return response()->json(User::where('id', $id)->first(), 200);
	}

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
			EmailVerificationController::sendEmail($user->name, $user->email, $user->verification_code, 'Account Confirmation', 'emails.register');
		}
		return response()->json('Registration is successful!', 200);
	}

	public function login(LoginRequest $request)
	{
		$username = $request->name;
		$password = $request->password;
		$usernameType = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		$token = auth()->attempt([$usernameType=>$username, 'password'=>$password]);
		if (!$token)
		{
			return response()->json(['error' => 'User Does not exist!'], 404);
		}

		return response()->json([
			'access_token'=> $token,
			'token_type'  => 'bearer',
			'expires_in'  => auth()->factory()->getTTL() * 60,
			'userData'    => auth()->user(),
		]);
	}

	public function logout()
	{
		auth()->logout();
		return response()->json(['message' => 'Successfully logged out']);
	}
}
