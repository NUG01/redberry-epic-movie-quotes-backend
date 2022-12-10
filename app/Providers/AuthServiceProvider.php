<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\BroadcastAuthGuard;
use App\Models\User;
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

		// Auth::provider('custom', function ($app, array $config) {
		// 	return new CustomAuthProvider($app->make('App\User'));
		// });

		Auth::extend('custom', function ($app, $name, array $config) {
			return new BroadcastAuthGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
		});

		return User::where('id', 1)->first();
		// Auth::viaRequest('custom', function ($request) {
		//   });

		// 		return User::find(1);

		// return User::where('id',1)->first();
		// Auth::viaRequest('jwt', function () {
		// 			$tokenPayload = JWT::decode($request->bearerToken(), new Key(config('auth.jwt_secret'), 'HS256'));

		// 		return \App\Models\User::find($tokenPayload)->first();
		// 	});
		// Auth::extend('jwt', function ($app, $name, array $config) {
		// Return an instance of Illuminate\Contracts\Auth\Guard...

		// 		return new JwtGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
		// });
		// 	Auth::viaRequest('broadcast-token', function ($request) {
		//     return User::where('id', 1)->first();
		// });

		// return User::find(1);
		// Auth::viaRequest('session', function (Request $request) {
		// 	$decoded = JWT::decode(
		// 		request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
		// 		new Key(config('auth.jwt_secret'), 'HS256')
		// 	);
		// 	return User::find($decoded->uid);
		// });
	}
}
