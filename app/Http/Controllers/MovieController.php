<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Models\Movie;

class MovieController extends Controller
{
	public function index($id)
	{
		return response()->json(Movie::where('user_id', $id)->get(), 200);
	}

	public function getAllMovies()
	{
		return response()->json(Movie::get(), 200);
	}

	public function create(AddMovieRequest $request, Movie $movie)
	{
		$this->updateOrCreateMovie($request, $movie);
		return response()->json('Movie added successfully!', 200);
	}

	public function destroy($id)
	{
		Movie::where('id', $id)->delete();
		return response()->json('Successfully deleted!', 200);
	}

	public function update(AddMovieRequest $request, Movie $movie)
	{
		$movie = $movie->where('id', $request->id)->first();
		$this->updateOrCreateMovie($request, $movie);
		return response()->json('Successfully updated!', 200);
	}

	private function updateOrCreateMovie($request, $movie)
	{
		$genre = explode(',', $request->genre);
		$movie->genre = $genre;
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
	}
}
