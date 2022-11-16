<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
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

Route::get('email-verification', [EmailVerificationController::class, 'verifyUser'])->name('verification.verify');

Route::controller(UserController::class)->group(function () {
	Route::get('auth-user', 'userData')->name('user.data');
	Route::post('update-profile', 'update')->name('update.profile');
	Route::post('update-email', 'submitChangeEmail')->name('update.email');
});

Route::controller(PasswordResetController::class)->group(function () {
	Route::post('forgot-password', 'submitForgetPasswordForm')->name('resetPassword.confirm');
	Route::post('reset-password', 'submitResetPasswordForm')->name('password.reset');
});

Route::controller(OAuthController::class)->group(function () {
	Route::get('auth/google/redirect', 'redirect')->middleware('web')->name('google.redirect');
	Route::get('auth/google/callback', 'callback')->middleware('web')->name('google.callback');
});

Route::controller(AuthController::class)->group(function () {
	Route::get('users', 'index')->name('users.index');
	Route::get('user/{id}', 'getGoogleUser')->name('users.googleUser');
	Route::post('register', 'register')->name('user.register');
	Route::post('login', 'login')->name('user.login');
	Route::post('logout', 'logout')->name('user.logout');
});
Route::controller(MovieController::class)->group(function () {
	Route::get('movies/{id}', 'index')->name('movies.index');
	Route::post('movies', 'create')->name('movie.create');
	Route::post('update-movie', 'update')->name('movie.update');
	Route::delete('movies/{id}', 'destroy')->name('movie.delete');
	Route::get('movies', 'getAllMovies')->name('movies.getMovies');
});
Route::controller(QuoteController::class)->group(function () {
	Route::get('quotes/{id}', 'index')->name('quotes.index');
	Route::post('quotes', 'create')->name('quotes.create');
	Route::post('update-quote', 'update')->name('quote.update');
	Route::delete('quotes/{id}', 'destroy')->name('quotes.delete');
	Route::get('quotes', 'getAllQuotes')->name('quotes.getQuotes');
	Route::get('quote/{id}', 'getChoosenQuote')->name('quote.show');
});
Route::controller(CommentController::class)->group(function () {
	Route::get('comments/{id}', 'index')->name('comments.index');
	Route::post('comments', 'create')->name('comments.create');
	Route::get('comments', 'getAllComments')->name('comments.getComments');
});
