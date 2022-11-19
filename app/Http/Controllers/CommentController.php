<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Comment::all(), 200);
	}

	public function show($quoteId): JsonResponse
	{
		$commentList = Comment::where('quote_id', $quoteId)->get();
		return response()->json($commentList, 200);
	}

	public function create(AddCommentRequest $request): JsonResponse
	{
		Comment::create([
			'body'     => $request->body,
			'quote_id' => $request->quote_id,
			'user_id'  => $request->user_id,
		]);

		return response()->json('Comment added successfully!', 200);
	}
}
