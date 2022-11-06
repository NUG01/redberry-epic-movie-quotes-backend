<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
	public function userData()
	{
		return response()->json(auth()->user(), 200);
	}
}
