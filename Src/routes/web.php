<?php

use App\Libs\Common;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserListCotroller;
use App\Http\Controllers\User\UserEditController;
use App\Http\Controllers\User\UserRegisterController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserChangePassController;
use App\Http\Controllers\User\UserStatusController;
use App\Http\Controllers\User\UserDraftController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Application\FormListController;
use App\Http\Controllers\Admin\AdminFlowSettingController;
use App\Http\Controllers\Application\Leave\LeaveApplicationController;
use App\Http\Controllers\LocaleController;

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

// set locale
Route::get('locale/{locale}', LocaleController::class)->where('locale', "(vi|en)")->name('locale');

/**
 * Authenticated user
 */
Route::middleware('auth')->group(function () {

    /**----------------------------------------*
     * Admin routes (for Admin role)
     *-----------------------------------------*/
    Route::middleware('can:admin-gate')->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            // Dashboard
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            // User managements
            Route::prefix('user')->name('user.')->group(function () {
                // List Users
                Route::get('/', [UserListCotroller::class, 'index'])->name('index');
                // Register new user
                Route::get('add', [UserRegisterController::class, 'create'])->name('add.create');
                Route::post('add', [UserRegisterController::class, 'store'])->name('add.store');
                // Edit user
                Route::get('edit/{user}', [UserEditController::class, 'show'])->name('edit.show');
                Route::post('edit/{user}', [UserEditController::class, 'update'])->name('edit.update');
                // Delete user
                Route::post('delete/{user}',[UserListCotroller::class, 'delete'])->name('delete');
            });
            //Approval Flow Setting
            Route::get('/flow-setting', [AdminFlowSettingController::class, 'index'])->name('flow.list');
            Route::get('/flow-setting/add', [AdminFlowSettingController::class, 'create'])->name('flow.create');
            Route::post('/flow-setting/add', [AdminFlowSettingController::class, 'store'])->name('flow.store');
            Route::get('/flow-setting/check', [AdminFlowSettingController::class, 'check'])->name('flow.check');
            Route::get('/flow-setting/edit/{id}', [AdminFlowSettingController::class, 'edit'])->name('flow.edit');
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
            Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
            // DRAFT
            Route::get('draft', [UserDraftController::class, 'index'])->name('draft');
            Route::post('draft/{id}', [UserDraftController::class, 'delete'])->name('draft.delete');
            // Status
            Route::get('status/{status}', [UserStatusController::class, 'index'])->name('status');
            // Form list
            Route::get('form', [FormListController::class, 'index'])->name('form.index');
            // Leave Application
            Route::get('leave/add',[LeaveApplicationController::class,'create'])->name('leave.create');
            Route::post('leave/add', [LeaveApplicationController::class, 'store'])->name('leave.store');
        });
    });

    /**----------------------------------------*
     * Common Routes
     *-----------------------------------------*/
    // Change pass
    Route::get('/changepass', [UserChangePassController::class, 'show'])->name('changepass.show');
    Route::post('/changepass', [UserChangePassController::class, 'update'])->name('changepass.update');
});
