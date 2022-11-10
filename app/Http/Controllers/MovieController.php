<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\AddMovieRequest;
use App\Models\Movie;
use App\Models\User;

class MovieController extends Controller
{
  public function create(AddMovieRequest $request, Movie $movie){
      $genre = explode(',',$request->genre);
      $movie->genre=$genre;
      $thumbnail = $request->file('thumbnail')->store('images');
      $movie->thumbnail=$thumbnail;
      $movie->user_id=auth()->user()->id;
      $movie->setTranslation('name','en',$request->name_en);
      $movie->setTranslation('name','ka',$request->name_ka);
      $movie->setTranslation('director','en',$request->director_en);
      $movie->setTranslation('director','ka',$request->director_ka);
      $movie->setTranslation('description','en',$request->description_en);
      $movie->setTranslation('description','ka',$request->description_ka);
      $movie->save();
      return response()->json('Movie added successfully!', 200);
    }


    public function index(){
      return response()->json(Movie::where('user_id', auth()->user()->id)->get(), 200);
    }
}
