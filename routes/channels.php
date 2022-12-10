<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('auth.{id}', function ($user, $id) {
// 	return true;
// return $id === jwtUser()->id;
// });

Broadcast::channel('private-notifications.{id}', function ($user, $id) {
	return true;
});

// Broadcast::channel('notifications.{userId}', function ($user, $id) {
// 	return true;
// });
