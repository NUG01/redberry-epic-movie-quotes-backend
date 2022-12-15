<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$genres = collect(['Drama', 'Fantasy', 'Anime', 'Fighting', 'Psychological', 'Mystical', 'other']);

		$genres->each(function ($genreName) {
			$genre = new Genre();
			$genre->name = $genreName;
			$genre->save();
		});
	}
}
