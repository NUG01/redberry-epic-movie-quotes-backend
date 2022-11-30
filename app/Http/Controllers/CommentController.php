<?php

namespace App\Http\Controllers;

use App\Events\NotificationStatusUpdated;
use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{

	public function index($quote): JsonResponse
	{
		$commentList = Comment::where('quote_id', $quote)->with('user:id,name,thumbnail')->get();
		return response()->json($commentList, 200);
	}

	public function create(AddCommentRequest $request, Notification $notification): JsonResponse
	{
		$comment=Comment::create([
			'body'     => $request->body,
			'quote_id' => $request->quote_id,
			'user_id'  => $request->user_id,
		]);
		$notification->body=$request->body;
		$notification->quote_id=$request->quote_id;
		$notification->user_id=$request->user_id;
		$notification->save();
		$user=User::find($request->user_id);
		$userData=['id'=>$user->id,'name'=>$user->name, 'thumbnail'=> $user->thumbnail];
		$notification = Notification::latest()->first();
		event(new NotificationStatusUpdated(['data'=>$notification, 'user'=>$userData, 'quoteAuthor'=>$notification->quote->user_id]));

		return response()->json('Comment added successfully!', 200);
	}
}
