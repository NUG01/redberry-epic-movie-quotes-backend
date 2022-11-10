<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded=['id'];

    public $translatable = ['name', 'director', 'description'];

    protected $casts = [
		'genre' => 'array',
	];


    public function user(): BelongsTo
    {
        return	$this->belongsTo(User::class);
        }
}
