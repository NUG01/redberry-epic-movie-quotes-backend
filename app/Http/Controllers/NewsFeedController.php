<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function index(): JsonResponse
	{
		$quotes = Quote::orderBy('id', 'DESC')->with('comments', 'comments.user:id,name,thumbnail', 'likes', 'user:id,name,thumbnail', 'movie:id,name')->paginate(5);
		return response()->json($quotes);
	}
}
