<?php

use App\Libs\Common;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\User\UserListCotroller;
use App\Http\Controllers\User\UserEditController;
use App\Http\Controllers\User\UserRegisterController;
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
    return redirect()->route('login');
});

/**
 * All routes of authentication
 */
Auth::routes([
    'verify' => false, // turn off
    'confirm' => false, // turn off
    'register' => false
]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Authenticated user
 */
Route::middleware('auth')->group(function () {

    /**----------------------------------------*
     * Admin routes (for Admin role)
     *-----------------------------------------*/
    Route::middleware('can:admin-gate')->group(function () {
        // Dashboard
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'show'])->name('dashboard');
        });
        Route::prefix('user')->name('user.')->group(function () {
            // List Users
            Route::get('/list', [UserListCotroller::class, 'show'])->name('list');
            // Register new user
            Route::get('register', [UserRegisterController::class, 'show'])->name('register.show');
            Route::post('register', [UserRegisterController::class, 'store'])->name('register.store');
            // Edit user
            Route::get('edit/{user}', [UserEditController::class, 'show'])->name('edit.show');
            Route::put('edit/{user}', [UserEditController::class, 'update'])->name('edit.update');
        });
    });

    /**----------------------------------------*
     * User routes
     *-----------------------------------------*/
    Route::prefix('user')->name('user.')->group(function () {
        /**----------------------------------------*
         * Only user role
         *-----------------------------------------*/
        Route::middleware('can:user-gate')->group(function () {
            // Dashboard
            Route::get('/dashboard', [UserDashboardController::class, 'show'])->name('dashboard');
        });
        /**----------------------------------------*
         * Both of Admin & User
         *-----------------------------------------*/
        // Change pass
        Route::get('changepass', [UserChangePassController::class, 'show'])->name('changepass.show');
        Route::put('changepass', [UserChangePassController::class, 'update'])->name('changepass.update');
    });

    /**----------------------------------------*
     * Common Routes
     *-----------------------------------------*/
    // set locale
    Route::get('locale/{lang}', function ($lang) {
        Common::setLocale($lang);
        return redirect()->back();
    })
    ->where('lang', "(vi|en)")
    ->name('locale');
});
