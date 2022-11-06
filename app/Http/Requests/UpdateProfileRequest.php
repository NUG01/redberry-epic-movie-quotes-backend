<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name'                 => ['required', 'min:3', 'max:15', Rule::unique('users')->ignore(auth()->user()->id, 'id')],
			'email'                => ['required', 'email', Rule::unique('users')->ignore(auth()->user()->id, 'id')],
			'password'             => 'required|min:8|max:15',
			'thumbnail'            => '',
		];
	}
}
