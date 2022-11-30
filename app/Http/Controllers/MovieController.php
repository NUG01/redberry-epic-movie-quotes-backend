<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{

	public function index($user): JsonResponse
	{
		return response()->json(Movie::where('user_id', $user)->with('quotes')->get(), 200);
	}

	public function show(Movie $movie): JsonResponse
	{
		$quotesData=Quote::where('movie_id', $movie->id)->with('likes', 'comments')->get();
		return response()->json(['movie'=>$movie, 'genres'=>$movie->genres, 'quotes'=>$quotesData,], 200);
	}

	public function create(AddMovieRequest $request, Movie $movie)
	{
		$this->updateOrCreateMovie($request, $movie);
		return response()->json('Movie added successfully!', 200);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json('Successfully deleted!', 200);
	}

	public function update(AddMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie = $movie->where('id', $request->id)->first();
		$this->updateOrCreateMovie($request, $movie);
		return response()->json('Successfully updated!', 200);
	}

	private function updateOrCreateMovie($request, $movie)
	{
		$thumbnail = $request->file('thumbnail')->store('images');
		$movie->thumbnail = $thumbnail;
		if ($request->user_id ? $movie->user_id = $request->user_id : null);
		$movie->setTranslation('name', 'en', $request->name_en);
		$movie->setTranslation('name', 'ka', $request->name_ka);
		$movie->setTranslation('director', 'en', $request->director_en);
		$movie->setTranslation('director', 'ka', $request->director_ka);
		$movie->setTranslation('description', 'en', $request->description_en);
		$movie->setTranslation('description', 'ka', $request->description_ka);
		$movie->save();
		$genre = explode(',', $request->genre);
		$movie_id = Movie::orderBy('created_at', 'desc')->first()->id;
		$count = count($genre);
		DB::table('genre_movie')->where('movie_id', $movie_id)->delete();
		for ($i = 0; $i < $count; $i++)
		{
			DB::table('genre_movie')->insert([
				'movie_id'=> $movie_id,
				'genre_id'=> $genre[$i],
			]);
		}
	}
}
