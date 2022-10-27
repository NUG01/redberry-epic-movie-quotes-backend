<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ResetPasswordController;
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

Route::controller(ResetPasswordController::class)->group(function () {
	Route::post('forgot-password', 'send')->name('forgotPassword.send');
	Route::post('reset-password-form', 'update')->name('resetPassword.form');
});

Route::controller(AuthController::class)->group(function () {
	Route::post('register', 'register')->name('register.user');
	Route::post('login', 'login')->name('user.login');
	Route::post('logout', 'logout')->name('user.logout');
});
