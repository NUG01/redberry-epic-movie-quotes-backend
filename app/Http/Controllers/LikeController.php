<?php

namespace App\Http\Controllers;

use App\Events\NotificationStatusUpdated;
use App\Http\Requests\AddLikeRequest;
use App\Models\Like;
use App\Models\Quote;
use App\Models\User;
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

	public function getUserLikes($userId): JsonResponse
	{
		$quotesData=Quote::where('user_id', $userId)->pluck('id');
		$likesData=Like::whereIn('quote_id', $quotesData)->with('user:id,name,thumbnail')->get();
		return response()->json($likesData, 200);
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
			$user=User::find($request->user_id);
			$userData=['name'=>$user->name, 'thumbnail'=> $user->thumbnail];
			$like = Like::latest()->first();
      event(new NotificationStatusUpdated(['data'=>$like, 'user'=>$userData, 'quoteAuthor'=>$like->quote->user_id]));
			return response()->json(['message'=>'Liked!', 'attributes'=>$like->all()], 200);
   	}
   }
}
