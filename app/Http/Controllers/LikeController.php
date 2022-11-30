<?php

namespace App\Http\Controllers;

use App\Events\NotificationStatusUpdated;
use App\Http\Requests\AddLikeRequest;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{

	public function index($quote): JsonResponse
	{
		$quoteLikes = Like::where('quote_id', $quote)->get();
		return response()->json($quoteLikes, 200);
	}

   public function create(AddLikeRequest $request, Like $like, Notification $notification): JsonResponse
   {
		 $alreadyLiked = $like->where('quote_id', $request->quote_id)->where('user_id', $request->user_id)->first();
		 $notificationExists = $notification->where('quote_id', $request->quote_id)->where('user_id', $request->user_id)->where('body', null)->first();
   	if ($alreadyLiked)
   	{
			 $alreadyLiked->delete();
			 $notificationExists->delete();
   		return response()->json(['message'=>'Unliked!', 'attributes'=>$like->all()], 200);
		}
		else
		{
			$like->quote_id = $request->quote_id;
			$like->user_id = $request->user_id;
			$like->save();
			$notification->quote_id=$request->quote_id;
			$notification->user_id=$request->user_id;
			$notification->save();
			$user=User::find($request->user_id);
			$userData=['id'=>$user->id,'name'=>$user->name, 'thumbnail'=> $user->thumbnail];
			$notification = Notification::latest()->first();
			event(new NotificationStatusUpdated(['data'=>$notification, 'user'=>$userData, 'quoteAuthor'=>$notification->quote->user_id]));
			return response()->json(['message'=>'Liked!', 'attributes'=>$like->all()], 200);
   	}
   }
}
