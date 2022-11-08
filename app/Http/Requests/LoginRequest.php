<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	public function rules()
	{
		return [
			'name'                  => ['required', 'min:3'],
			'password'              => ['required'],
			'remember_device'       => [''],
		];
	}
}
