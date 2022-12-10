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
use Illuminate\Support\Facades\App;
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
	Route::get('user', 'index')->name('user.index');
	Route::patch('user/profile', 'update')->name('user.update');
	Route::post('user/email', 'submitChangeEmail')->name('user.edit');
	Route::post('user/newEmail', 'addNewEmail')->name('user.store');
	Route::delete('user/email/{email}', 'destroy')->name('user.destroy');
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
	Route::get('movies', 'index')->name('movies.index');
	Route::get('movies/{movie}/details', 'show')->name('movies.show');
	Route::post('movies', 'create')->name('movies.create');
	Route::patch('movies', 'update')->name('movies.update');
	Route::delete('movies/{movie}', 'destroy')->name('movies.destroy');
});

Route::controller(QuoteController::class)->group(function () {
	Route::get('quotes', 'index')->name('quotes.index');
	Route::get('quotes/{movie:id}', 'show')->name('quotes.show');
	Route::get('quotes/{quote}/details', 'quote')->name('quotes.details');
	Route::post('quotes', 'create')->name('quotes.create');
	Route::patch('quotes/{quote}', 'update')->name('quotes.update');
	Route::delete('quotes/{quote}', 'destroy')->name('quotes.destroy');
});

Route::controller(CommentController::class)->group(function () {
	Route::post('comments', 'create')->name('comments.create');
});

Route::controller(LikeController::class)->group(function () {
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

Route::get('swagger', fn () => App::isProduction() ? response(status:403) : view('swagger'))->name('swagger');
