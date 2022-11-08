<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name'                 => ['required','min:3','max:15','unique:users,name'],
			'email'                => ['required','unique:users,email','email'],
			'password'             => ['required','min:8','max:15'],
			'password_confirmation'=> ['required','same:password'],
		];
	}
}
