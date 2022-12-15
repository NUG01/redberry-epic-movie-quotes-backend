<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuotesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$table->id();
		$table->foreignId('movie_id')->constrained()->cascadeOnDelete();
		$table->foreignId('user_id')->constrained();
		$table->json('quote');
		$table->string('thumbnail')->nullable();
		$table->timestamps();
	}
}
