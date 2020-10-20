<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\UserEditController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

/**
 * All routes of authentication
 */
Auth::routes([
    'verify' => false, // turn off
    'confirm' => false, // turn off
    'register' => true
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Authenticated user
 */
Route::middleware('auth')->group(function () {
    /**
     * Admin routes
     */
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    });
    /**
     * User mangement routes
     */
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('edit/{user}', [UserEditController::class, 'show'])->name('show');
        Route::put('edit/{user}', [UserEditController::class, 'edit'])->name('edit');
    });
});
