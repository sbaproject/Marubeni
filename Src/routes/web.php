<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;

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

// allows only guest users (un-logged-in users)
Route::middleware('guest')->group(function () {
	// register
	Route::get('/auth/register', [RegisterController::class, 'show'])->name('register');
	// login
	Route::get('/auth/login', [LoginController::class, 'show'])->name('login');
	Route::post('/auth/login', [LoginController::class, 'authenticate'])->name('authenticate');
});

// allows only logged-in users
Route::middleware('auth')->group(function () {
	// admin
	Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
	// logout
	Route::get('/auth/logout', [LoginController::class, 'logout'])->name('logout');
});
