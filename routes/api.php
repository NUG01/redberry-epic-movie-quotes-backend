<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::controller(UserController::class)->group(function () {
	Route::get('user', 'user')->name('user.get');
	Route::get('user/{id}', 'getGoogleUser')->name('user.googleUser');
	Route::post('user/profile', 'update')->name('update.profile');
	Route::post('user/newEmail', 'addNewEmail')->name('user.newEmail');
	Route::post('user/email', 'submitChangeEmail')->name('update.email');
	Route::post('user/email/{email:id}', 'destroy')->name('delete.email');
});

Route::controller(PasswordResetController::class)->group(function () {
	Route::post('forgot-password', 'submitForgetPasswordForm')->name('resetPassword.confirm');
	Route::post('reset-password', 'submitResetPasswordForm')->name('password.reset');
});

Route::controller(OAuthController::class)->group(function () {
	Route::get('auth/google/redirect', 'redirect')->middleware('web')->name('google.redirect');
	Route::get('auth/google/callback', 'callback')->name('google.callback');
});

Route::controller(AuthController::class)->group(function () {
	Route::post('register', 'register')->name('user.register');
	Route::post('login', 'login')->name('user.login');
	Route::post('logout', 'logout')->name('user.logout');
});

Route::controller(MovieController::class)->group(function () {
	Route::get('movies/{user:id}', 'index')->name('movies.index');
	Route::get('movies/{movie:id}/details', 'show')->name('movies.get');
	Route::post('movies/create', 'create')->name('movies.create');
	Route::post('movies', 'update')->name('movies.update');
	Route::delete('movies/{movie:id}', 'destroy')->name('movies.delete');
});

Route::controller(QuoteController::class)->group(function () {
	Route::get('quotes', 'index')->name('quotes.index');
	Route::get('quotes/{movie:id}', 'show')->name('quotes.show');
	Route::get('quotes/{quote:id}/details', 'quoteDetails')->name('quotes.details');
	Route::post('quotes/create', 'create')->name('quotes.create');
	Route::post('quotes', 'update')->name('quotes.update');
	Route::delete('quotes/{quote:id}', 'destroy')->name('quotes.delete');
});

Route::controller(CommentController::class)->group(function () {
	Route::get('comments/{quote:id}', 'index')->name('comments.index');
	Route::post('comments', 'create')->name('comments.create');
});

Route::controller(LikeController::class)->group(function () {
	Route::get('likes/{quote:id}', 'index')->name('likes.index');
	Route::post('likes', 'create')->name('likes.create');
});

Route::controller(NotificationController::class)->group(function () {
	Route::get('notifications/{user:id}', 'index')->name('notifications.index');
});

Route::controller(GenreController::class)->group(function () {
	Route::get('genres', 'index')->name('genres.index');
});

Route::controller(EmailVerificationController::class)->group(function () {
	Route::get('email-verification', 'verifyUser')->name('verification.verify');
});

// Broadcast::routes(['middleware' => ['jwt.auth']]);
// Broadcast::routes(['prefix'=> 'api', 'middleware' => ['jwt.auth']]);
