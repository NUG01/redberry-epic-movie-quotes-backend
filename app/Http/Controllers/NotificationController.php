<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index($user){
        $quotesData=Quote::where('user_id', $user)->pluck('id');
		$notifications=Notification::whereIn('quote_id', $quotesData)->where('user_id', '!=', $user)->with('user:id,name,thumbnail')->latest()->get();
        return response()->json($notifications, 200);
    }
}
