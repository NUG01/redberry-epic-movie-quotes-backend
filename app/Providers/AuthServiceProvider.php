<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Services\Auth\JwtGuard;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
			$this->registerPolicies();
			Auth::extend('broadcast-token', function ($app, $name, array $config) {
				// Return an instance of Illuminate\Contracts\Auth\Guard...

				return new JwtGuard(Auth::createUserProvider($config['provider']));
		});

			return User::find(1);
			Auth::viaRequest('broadcast-token', function (Request $request) {
				$decoded = JWT::decode(
					request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
					new Key(config('auth.jwt_secret'), 'HS256')
				);
				return User::find($decoded->uid);
    });
	}
}
