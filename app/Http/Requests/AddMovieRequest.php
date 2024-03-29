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
			'genre'         => ['required', 'string'],
			'director_en'   => ['required', 'string'],
			'director_ka'   => ['required', 'string'],
			'description_en'=> ['required', 'string'],
			'description_ka'=> ['required', 'string'],
			'thumbnail'     => [],
			'id'            => ['numeric'],
			'user_id'       => ['numeric'],
		];
	}
}
