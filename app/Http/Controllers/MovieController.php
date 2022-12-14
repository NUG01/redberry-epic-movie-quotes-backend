<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Movie::where('user_id', jwtUser()->id)->with('quotes')->get());
	}

	public function show(Movie $movie): JsonResponse
	{
		$quotes = Quote::where('movie_id', $movie->id)->with('likes', 'comments', 'user:id,name,thumbnail')->get();
		return response()->json(['movie'=>$movie->load('user:id,name,thumbnail'), 'genres'=>$movie->genres, 'quotes'=>$quotes]);
	}

	public function create(AddMovieRequest $request, Movie $movie)
	{
		$this->updateOrCreateMovie($request, $movie);
		return response()->json('Movie added successfully!');
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json('Successfully deleted!');
	}

	public function update(AddMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie = $movie->where('id', $request->id)->first();
		$this->updateOrCreateMovie($request, $movie);
		return response()->json('Successfully updated!');
	}

	private function updateOrCreateMovie($request, $movie)
	{

	  if($request->file('thumbnail'))	$movie->thumbnail = $request->file('thumbnail')->store('images');
		if ($request->user_id ? $movie->user_id = $request->user_id : null);
		$movie->setTranslation('name', 'en', $request->name_en);
		$movie->setTranslation('name', 'ka', $request->name_ka);
		$movie->setTranslation('director', 'en', $request->director_en);
		$movie->setTranslation('director', 'ka', $request->director_ka);
		$movie->setTranslation('description', 'en', $request->description_en);
		$movie->setTranslation('description', 'ka', $request->description_ka);
		$movie->save();
		$movie->id ? $movie_id = $movie->id : $movie_id = Movie::orderBy('id', 'desc')->first()->id;
		$genre = explode(',', $request->genre);
		DB::table('genre_movie')->where('movie_id', $movie_id)->delete();
		$movie->genres()->attach($genre);
	}
}
