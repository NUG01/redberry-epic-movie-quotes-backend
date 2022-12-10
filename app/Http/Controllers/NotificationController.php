<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
	public function index(User $user)
	{
		$quotes = $user->quotes->pluck('id');
		$notifications = Notification::whereIn('quote_id', $quotes)->where('user_id', '!=', $user)->with('user:id,name,thumbnail')->latest()->get();
		return response()->json($notifications);
	}
}
