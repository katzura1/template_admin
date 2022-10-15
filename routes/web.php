<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
 */

Route::get('login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::get('recover-password', [AuthController::class, 'recoverPassword'])->name('password.reset');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::get('verify-email', [HomeController::class, 'index'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyAccount'])->middleware(['guest', 'signed'])->name('verification.verify');

Route::post('login/authenticate', [AuthController::class, 'authenticate'])->middleware('guest')->name('login.authenticate');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('forgot-password', [AuthController::class, 'sendEmailForgot'])->name('password.email');
Route::post('recover-password', [AuthController::class, 'changePassword'])->name('password.update');
Route::post('register', [AuthController::class, 'registerAccount'])->name('register.user');
Route::post('email/verification-notification', [AuthController::class, 'resendVerifyEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('test')->middleware('verified');
