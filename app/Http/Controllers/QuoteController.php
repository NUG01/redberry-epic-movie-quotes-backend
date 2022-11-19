<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function index($movieId): JsonResponse
	{
		$quoteList = Quote::where('movie_id', $movieId)->get();
		return response()->json($quoteList, 200);
	}

	public function getQuotesForNewsFeed(): JsonResponse
	{
		return response()->json(Quote::latest()->get(), 200);
	}

	public function show(Quote $quote): JsonResponse
	{
		return response()->json($quote, 200);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('Successfully deleted1!', 200);
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
		return response()->json(['message'=>'Quote added successfully!', 'attributes'=> $quote->latest()->get()], 200);
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
