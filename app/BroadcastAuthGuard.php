<?php

namespace App;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class BroadcastAuthGuard implements Guard
{
	private $request;

	private $provider;

	private $user;

	public function __construct(UserProvider $provider, Request $request)
	{
		$this->request = $request;
		$this->provider = $provider;
		$this->user = null;
	}

	public function check()
	{
		return isset($this->user);
	}

	public function guest()
	{
		return !isset($this->user);
	}

	public function user()
	{
		return User::where('id', 1)->first();
		if (isset($this->user))
		{
			return $this->user;
		}

		// if (!request()->cookie('access_token') && !request()->header('Authorization'))
		// {
		// 	return null;
		// }

		// $decoded = JWT::decode(
		// 	request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
		// 	new Key(config('auth.jwt_secret'), 'HS256')
		// );
		// return User::find($decoded->uid);
	}

	public function id()
	{
		if (isset($this->user))
		{
			return $this->user->getAuthIdentifier();
		}
	}

	public function validate(array $credentials = [])
	{
		if (!isset($credentials['email']) || empty($credentials['email']) || !isset($credentials['password']) || empty($credentials['password']))
		{
			return false;
		}

		$user = $this->provider->retrieveById($credentials['email']);

		if (!isset($user))
		{
			return false;
		}

		if ($this->provider->validateCredentials($user, $credentials))
		{
			$this->setUser($user);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function setUser(Authenticatable $user)
	{
		$this->user = $user;
	}

	public function hasUser()
	{
	}
}
