<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserEditController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserChangePassController;
use App\Http\Controllers\Admin\AdminDashboardController;

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
    Route::middelware('role:'.ROLE['Admin'])->group(function () {

        Route::prefix('admin')->name('admin.')->group(function(){
            Route::get('/dashboard', [AdminDashboardController::class, 'show'])->name('dashboard');
        });
        /**
         * register user
         */
        Route::get('/user/register', [UserEditController::class, 'show'])->name('user.register.show');
        Route::post('/user/register', [UserEditController::class, 'store'])->name('user.register.store');
    });
    /**
     * User mangement routes
     */
    Route::prefix('user')->name('user.')->group(function () {
        /**
         * Dashboard
         */
        Route::get('/dashboard', [UserDashboardController::class, 'show'])->name('dashboard');
        /**
         * edit
         */
        Route::get('edit/{user}', [UserEditController::class, 'show'])->name('edit.show');
        Route::put('edit/{user}', [UserEditController::class, 'update'])->name('edit.update');
        /**
         * change pass
         */
        Route::get('changepass', [UserChangePassController::class, 'show'])->name('changepass.show');
        Route::put('changepass', [UserChangePassController::class, 'update'])->name('changepass.update');
    });
});
