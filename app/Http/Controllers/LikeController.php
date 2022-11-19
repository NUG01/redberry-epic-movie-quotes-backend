<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddLikeRequest;
use App\Models\Like;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Like::all(), 200);
	}

	public function show($quoteId): JsonResponse
	{
		$quoteLikes = Like::where('quote_id', $quoteId)->get();
		return response()->json($quoteLikes, 200);
	}

   public function create(AddLikeRequest $request, Like $like): JsonResponse
   {
   	$alreadyLiked = $like->where('quote_id', $request->quote_id)->where('user_id', $request->user_id)->first();
   	if ($alreadyLiked)
   	{
   		$alreadyLiked->delete();
   		return response()->json(['message'=>'Unliked!', 'attributes'=>$like->all()], 200);
   	}
   	else
   	{
   		$like->quote_id = $request->quote_id;
   		$like->user_id = $request->user_id;
   		$like->save();
   		return response()->json(['message'=>'Liked!', 'attributes'=>$like->all()], 200);
   	}
   }
}
