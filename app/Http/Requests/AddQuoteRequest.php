<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'quote_en'   => ['required', 'string'],
			'quote_ka'   => ['required', 'string'],
			'thumbnail'  => [],
			'id'         => ['numeric'],
			'quote_id'   => ['numeric'],
			'user_id'    => ['numeric'],
		];
	}
}
