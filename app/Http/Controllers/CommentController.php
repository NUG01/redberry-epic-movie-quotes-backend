<?php

namespace App\Http\Controllers;

use App\Events\NotificationStatusUpdated;
use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function create(AddCommentRequest $request): JsonResponse
	{
		$validated = $request->validated();
		Comment::create($validated);
		$notification = Notification::create($validated);
		$notification = Notification::latest()->first();
		event(new NotificationStatusUpdated(['data'=>$notification, 'user'=>jwtUser(), 'quoteAuthorId'=>$notification->quote->user_id]));

		return response()->json('Comment added successfully!');
	}
}
