<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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
		$username = $request->name;
		$password = $request->password;
		$this->validated = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		$token = null;
		if (auth()->attempt([$this->validated=>$username, 'password'=>$password, 'is_verified' => 1], $request->has('remember_device')))
		{
			$token = bin2hex(random_bytes(32));
			// $token = auth()->attempt($request->all());
		}


		if (!$token)
		{
			return response()->json(['error' => 'User Does not exist!'], 404);
		}
		if($this->validated=='email'){
			$user=User::where('email', $username)->first();
		}else{
			$user=User::where('name', $username)->first();

		}

		return response()->json([
			'access_token'=> $token,
			'token_type'  => 'bearer',
			'expires_in'  => auth()->factory()->getTTL() * 60,
			'id'=> $user->id,
		]);
	}

	public function logout(Request $request)
	{
		// return $request;
		if(User::where('google_auth_token', $request->token)->first()){
			$user=User::where('google_auth_token', $request->token)->first();
			$user->google_auth_token=null;
			$user->save();
   }
		//  auth()->logout();
		//  JWTAuth::parseToken()->invalidate( true );

	 return response()->json(['message' => 'Successfully logged out']);


	}


	public function userData(Request $request){
		$user= User::where('id', $request->id)->first();
		return response()->json($user, 200);
	}
}
