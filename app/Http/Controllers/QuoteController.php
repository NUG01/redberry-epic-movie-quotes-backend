<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	
	public function index(): JsonResponse
	{
		$quotesData=Quote::orderBy('id', 'DESC')->with('comments','comments.user:id,name,thumbnail', 'likes', 'user:id,name,thumbnail', 'movie:id,name')->paginate(5);
		return response()->json($quotesData, 200);
	}

	
	public function show($movie): JsonResponse
	{
		$quoteList = Quote::where('movie_id', $movie)->with('likes', 'comments')->get();
		return response()->json($quoteList, 200);
	}






	public function quoteDetails(Quote $quote): JsonResponse
	{
		$commentsData=Comment::where('quote_id', $quote->id)->with('user:id,name,thumbnail')->get();
		return response()->json(['quote'=>$quote, 'comments'=> $commentsData, 'likes'=>$quote->likes], 200);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('Successfully deleted!', 200);
	}

	public function update(AddQuoteRequest $request): JsonResponse
	{
		$quote = Quote::where('id', $request->quote_id)->first();
		$this->updateOrCreateQuote($request, $quote);
		return response()->json('Successfully updated!', 200);
	}

	public function create(AddQuoteRequest $request, Quote $quote): JsonResponse
	{
		$this->updateOrCreateQuote($request, $quote);
		$quotesData=Quote::with('comments','comments.user:id,name,thumbnail', 'likes', 'user:id,name,thumbnail', 'movie:id,name')->latest()->get();
		return response()->json(['message'=>'Quote added successfully!', 'attributes'=> $quotesData], 200);
	}

	private function updateOrCreateQuote($request, $quote)
	{
		if ($request->id ? $quote->movie_id = $request->id : null);
		if ($request->user_id ? $quote->user_id = $request->user_id : null);
		$thumbnail = $request->file('thumbnail')->store('images');
		$quote->thumbnail = $thumbnail;
		$quote->setTranslation('quote', 'en', $request->quote_en);
		$quote->setTranslation('quote', 'ka', $request->quote_ka);
		$quote->save();
	}
}
