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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::get('recover-password', [AuthController::class, 'recoverPassword'])->name('password.reset');
Route::post('login/authenticate', [AuthController::class, 'authenticate'])->name('login.authenticate')->middleware('guest');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('forgot-password', [AuthController::class, 'sendEmailForgot'])->name('password.email');
Route::post('recover-password', [AuthController::class, 'changePassword'])->name('password.update');
