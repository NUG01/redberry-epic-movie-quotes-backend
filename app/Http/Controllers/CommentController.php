<?php

namespace App\Http\Controllers;

use App\Events\NotificationStatusUpdated;
use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Comment::with('user:id,name,thumbnail',)->get(), 200);
	}

	public function show($quoteId): JsonResponse
	{
		$commentList = Comment::where('quote_id', $quoteId)->with('user:id,name,thumbnail')->get();
		return response()->json($commentList, 200);
	}

	public function getUserComments($userId): JsonResponse
	{
		$quotesData=Quote::where('user_id', $userId)->pluck('id');
		$commentsData=Comment::whereIn('quote_id', $quotesData)->with('user:id,name,thumbnail')->get();
		return response()->json($commentsData, 200);
	}

	public function create(AddCommentRequest $request): JsonResponse
	{
		Comment::create([
			'body'     => $request->body,
			'quote_id' => $request->quote_id,
			'user_id'  => $request->user_id,
		]);
		$user=User::find($request->user_id);
		$userData=['name'=>$user->name, 'thumbnail'=> $user->thumbnail];
		$comment = Comment::latest()->first();
		event(new NotificationStatusUpdated(['data'=>$comment, 'user'=>$userData, 'quoteAuthor'=>$comment->quote->user_id]));

		return response()->json('Comment added successfully!', 200);
	}
}
