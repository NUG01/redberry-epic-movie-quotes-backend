<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Models\Quote;

class QuoteController extends Controller
{
	public function index($id)
	{
		$quote = Quote::where('movie_id', $id)->get();
		return response()->json($quote, 200);
	}

	public function getChoosenQuote($id)
	{
		$quote = Quote::where('movie_id', $id)->get();
		return response()->json($quote, 200);
	}

	public function destroy($id)
	{
		Quote::where('id', $id)->delete();
		return response()->json('Successfully deleted!', 200);
	}

	public function update(AddQuoteRequest $request)
	{
		$quote = Quote::where('id', $request->quote_id)->first();
		$this->updateOrCreateQuote($request, $quote);
		return response()->json('Successfully updated!', 200);
	}

	public function create(AddQuoteRequest $request, Quote $quote)
	{
		$this->updateOrCreateQuote($request, $quote);
		return response()->json('Quote added successfully!', 200);
	}

	private function updateOrCreateQuote($request, $quote)
	{
		if ($request->id ? $quote->movie_id = $request->id : null);
		$thumbnail = $request->file('thumbnail')->store('images');
		$quote->thumbnail = $thumbnail;
		$quote->setTranslation('quote', 'en', $request->quote_en);
		$quote->setTranslation('quote', 'ka', $request->quote_ka);
		$quote->save();
	}
}
