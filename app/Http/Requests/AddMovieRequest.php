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
            'name_en'       =>['required','unique:movies,name'],
            'name_ka'       =>['required','unique:movies,name'],
            'genre'         =>['required'],
            'director_en'   =>['required'],
            'director_ka'   =>['required'],
            'description_en'=>['required'],
            'description_ka'=>['required'],
            'thumbnail'     =>['image']
        ];
    }
}
