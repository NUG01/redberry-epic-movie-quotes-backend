<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirect()
	{
		return Socialite::driver('google')->redirect();
	}

	public function authToken(Request $request)
	{
		$user = DB::table('users')->where('email', $request->email)->where('google_id', 1)->where('google_auth_token', $request->token)->first();
		if ($user)
		{
			return response()->json([
				'access_token'=> $user->google_auth_token,
				'token_type'  => 'bearer',
				'expires_in'  => auth()->factory()->getTTL() * 60,
				'email'       => $request->email,
			]);
		}
		else
		{
			return response()->json('User not found!', 401);
		}
	}

	public function callback()
	{
		try
		{
			$google_user = Socialite::driver('google')->user();
			$user = User::where('email', $google_user->email)->first();
			if ($user)
			{
				$token = bin2hex(random_bytes(32));
				$user = User::where('email', $user->email)->first();
				$user->google_auth_token = $token;
				$user->save();
				return redirect()->away(env('FRONTEND_URL') . '/google/login/' . $token . '?code=' . $user->email);
			}
			else
			{
				$random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
				User::create([
					'name'             => $google_user->name,
					'email'            => $google_user->email,
					'is_verified'      => 1,
					'password'         => Hash::make($random),
					'google_id'        => 1,
				]);

				return redirect(env('FRONTEND_URL') . '/landing/login');
			}
		}
		catch (Exception $e)
		{
			return redirect(env('BACK_URL') . '/auth/google/redirect');
		}
	}
}
