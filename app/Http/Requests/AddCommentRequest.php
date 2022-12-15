<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'body'     => ['required', 'string'],
			'quote_id' => ['required', 'numeric'],
			'user_id'  => ['required', 'numeric'],
		];
	}
}
