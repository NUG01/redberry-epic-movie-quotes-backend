<?php

namespace App\Http\Controllers;

use App\Events\NotificationStatusUpdated;
use App\Http\Requests\AddLikeRequest;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function create(AddLikeRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$alreadyLiked = Like::where('quote_id', $request->quote_id)->where('user_id', $request->user_id)->first();
		$notificationExists = Notification::where('quote_id', $request->quote_id)->where('user_id', $request->user_id)->where('body', null)->first();
		if ($alreadyLiked)
		{
			$alreadyLiked->delete();
			$notificationExists->delete();
			return response()->json(['message'=>'Unliked!', 'attributes'=>Like::where('quote_id', $request->quote_id)->get()]);
		}
		else
		{
			Like::create($validated);
			$notification = Notification::create($validated);
			event(new NotificationStatusUpdated(['data'=>$notification, 'user'=>jwtUser(), 'quoteAuthorId'=>$notification->quote->user_id]));
			return response()->json(['message'=>'Liked!', 'attributes'=>Like::where('quote_id', $request->quote_id)->get()]);
		}
	}
}
