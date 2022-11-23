<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
	public function redirect(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback(): RedirectResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		$user = User::updateOrCreate(
			[
				'google_id' => $googleUser->id,
			],
			[
				'name'                 => $googleUser->name,
				'email'                => $googleUser->email,
				'google_token'         => $googleUser->token,
				'password'             => bcrypt($googleUser->id),
				'is_verified'          => 1,
				'google_refresh_token' => $googleUser->refreshToken,
			]
		);

		$token = auth()->attempt(['email' => $googleUser->email, 'password'=>$googleUser->id]);
		if (!$token)
		{
			return response()->json(['error' => 'User Does not exist!'], 401);
		}

		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => User::where('email', $googleUser->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
		$cookie = cookie('access_token', $jwt, 30, '/', env('FRONTEND_URL'), true, true, false, 'Strict');
		return redirect(env('FRONTEND_URL_FOR_CONFIRM') . '/oauth' . '?user_id=' . $user->id)->withCookie($cookie);
	}
}
