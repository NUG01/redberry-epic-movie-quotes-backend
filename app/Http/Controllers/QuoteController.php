<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{

	public function index(Movie $movie): JsonResponse
	{
		$quotes = $movie->quotes->load('likes', 'comments');
		return response()->json($quotes);
	}

	public function show(Quote $quote): JsonResponse
	{
		$quote->load('comments', 'comments.user:id,name,thumbnail', 'likes', 'user:id,name,thumbnail');
		return response()->json(['quote'=>$quote]);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('Successfully deleted!');
	}

	public function update(Quote $quote, AddQuoteRequest $request): JsonResponse
	{
		$this->updateOrCreateQuote($request, $quote);
		return response()->json('Successfully updated!');
	}

	public function create(AddQuoteRequest $request, Quote $quote): JsonResponse
	{
		$this->updateOrCreateQuote($request, $quote);
		$quotes = Quote::where('id', $this->updateOrCreateQuote($request, $quote))->with('comments', 'comments.user:id,name,thumbnail', 'likes', 'user:id,name,thumbnail', 'movie:id,name')->get();
		return response()->json(['message'=>'Quote added successfully!', 'attributes'=> $quotes]);
	}

	private function updateOrCreateQuote($request, $quote)
	{
		if ($request->id ? $quote->movie_id = $request->id : null);
		if ($request->user_id ? $quote->user_id = $request->user_id : null);
		if($request->file('thumbnail')){
			$thumbnail = $request->file('thumbnail')->store('images');
			$quote->thumbnail = $thumbnail;
		} 
		$quote->setTranslation('quote', 'en', $request->quote_en);
		$quote->setTranslation('quote', 'ka', $request->quote_ka);
		$quote->save();
		return $quote->id;
	}
}
