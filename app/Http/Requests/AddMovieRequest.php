<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name_en'       => ['required', 'string'],
			'name_ka'       => ['required', 'string'],
			'genre'         => ['required', 'array'],
			'director_en'   => ['required', 'string'],
			'director_ka'   => ['required', 'string'],
			'description_en'=> ['required', 'string'],
			'description_ka'=> ['required', 'string'],
			'thumbnail'     => ['required', 'image'],
			'id'            => ['numeric'],
			'user_id'       => ['numeric'],
		];
	}
}
