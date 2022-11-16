<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
	public function redirect()
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback()
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

		auth()->user($user);
		$token = auth()->attempt(['name' => $googleUser->name, 'email' => $googleUser->email, 'password'=>$googleUser->id]);
		return redirect(env('FRONTEND_URL') . '/oauth' . '?token=' . $token . '&user_id=' . $user->id . '&expires_in=' . auth()->factory()->getTTL() * 60 . '&token_type=bearer');
	}
}
