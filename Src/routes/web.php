<?php

use App\Http\Controllers\User\UserController;
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

// allows only logged-in users
// Route::middleware('auth')->group(function(){
// 	Route::get('/', [UserController::class, 'index']);
// });

// allows only guest users
Route::middleware('guest')->group(function () {
	Route::get('/', [UserController::class, 'index']);
});
