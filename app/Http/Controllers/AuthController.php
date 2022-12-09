<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
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
		}else{
			return response()->json('Registration failed!', 403);
   }
		return response()->json('Registration is successful!', 200);
	}

	public function login(LoginRequest $request)
	{
		$secondaryEmailToken=null;
		$checkPassword=null;
		$username = $request->name;
		$password = $request->password;
		$usernameType = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		$token = auth()->attempt([$usernameType=>$username, 'password'=>$password]);
		if(!$token){
			$secondaryEmailToken=Email::where('address', $username)->first();
     }
		if(Email::where('address', $username)->first()){
			$emailUser=Email::where('address', $username)->first()->user;
			$checkPassword=auth()->attempt(['name'=>$emailUser->name, 'password'=>$password]);
     };
		if (!$token && ($secondaryEmailToken==null || $secondaryEmailToken->is_verified==0 || $checkPassword!=1))
		{
			return response()->json(['error' => 'User Does not exist!'], 403);
		}

		if($secondaryEmailToken!=null){
			$payload = [
				'exp' => Carbon::now()->addMinutes(30)->timestamp,
				'uid' => $emailUser->id,
			];
    }else{
			$payload = [
				'exp' => Carbon::now()->addMinutes(30)->timestamp,
				'uid' => User::where($usernameType, $username)->first()->id,
			];

		}


		
		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
		
		$cookie = cookie('access_token', $jwt, 30, '/', env('FRONTEND_URL'), true, true, false, 'Strict');
		return response()->json('Logged in successfully!', 200)->withCookie($cookie);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', env('FRONTEND_URL'), true, true, false, 'Strict');

		return response()->json(['message' => 'Successfully logged out'], 200)->withCookie($cookie);
	}
}
