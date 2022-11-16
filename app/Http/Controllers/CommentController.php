<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
	public function index($id)
	{
		$commentList = Comment::where('quote_id', $id)->get();
		return response()->json($commentList, 200);
	}

	public function getAllComments()
	{
		return response()->json(Comment::get(), 200);
	}

	public function create(AddCommentRequest $request)
	{
		Comment::create([
			'body'     => $request->body,
			'quote_id' => $request->quote_id,
			'user_id'  => $request->user_id,
		]);

		return response()->json('Comment added successfully!', 200);
	}
}
