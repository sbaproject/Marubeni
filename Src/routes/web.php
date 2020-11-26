<?php

use App\Libs\Common;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\CheckipController;
use App\Http\Controllers\User\ConfirmController;
use App\Http\Controllers\User\UserListCotroller;
use App\Http\Controllers\User\ApprovalController;
use App\Http\Controllers\User\UserEditController;
use App\Http\Controllers\User\UserDraftController;
use App\Http\Controllers\Error\ErrorPageController;
use App\Http\Controllers\User\UserStatusController;
use App\Http\Controllers\User\UserCompanyController;
use App\Http\Controllers\Admin\AdminBudgetController;
use App\Http\Controllers\Admin\AdminStatusController;
use App\Http\Controllers\User\UserRegisterController;
use App\Http\Controllers\Admin\AdminCompanyController;

use App\Http\Controllers\User\UserDashboardController;

use App\Http\Controllers\User\UserChangePassController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Application\FormListController;
use App\Http\Controllers\Admin\AdminFlowSettingController;
use App\Http\Controllers\Application\Business\BusinessTripController;
use App\Http\Controllers\Application\Leave\LeaveApplicationController;
use App\Http\Controllers\Application\Entertainment\EntertainmentController;

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

// error page
Route::get('404',[ErrorPageController::class, 'notfound'])->name('404');
Route::get('403', [ErrorPageController::class, 'forbidden'])->name('403');

/**
 * Authenticated user
 */
Route::middleware('auth')->group(function () {

    /**----------------------------------------*
     * Admin routes (for Admin role)
     *-----------------------------------------*/
    Route::get('checkip', [CheckipController::class, 'index'])->name('checkip');
    Route::post('checkip', [CheckipController::class, 'confirm'])->name('confirm');

    // Route::middleware('checkip')->group(function () {

        Route::middleware('can:admin-gate')->group(function () {
            Route::prefix('admin')->name('admin.')->group(function () {
                // Dashboard
                Route::get('dashboard/{id}', [AdminDashboardController::class, 'index'])->name('dashboard');
                // User managements
                Route::prefix('user')->name('user.')->group(function () {
                    // List Users
                    Route::get('/', [UserListCotroller::class, 'index'])->name('index');
                    // Register new user
                    Route::get('add', [UserRegisterController::class, 'create'])->name('create');
                    Route::post('add', [UserRegisterController::class, 'store'])->name('store');
                    // Edit user
                    Route::get('edit/{user}', [UserEditController::class, 'show'])->name('show');
                    Route::post('edit/{user}', [UserEditController::class, 'update'])->name('update');
                    // Delete user
                    Route::post('delete/{user}', [UserListCotroller::class, 'delete'])->name('delete');
                });
                //Approval Flow Setting
                Route::prefix('flow-setting')->name('flow.')->group(function () {
                    Route::get('/', [AdminFlowSettingController::class, 'index'])->name('index');
                    Route::get('add', [AdminFlowSettingController::class, 'create'])->name('create');
                    Route::post('add', [AdminFlowSettingController::class, 'store'])->name('store');
                    Route::get('check', [AdminFlowSettingController::class, 'check'])->name('check');
                    Route::get('edit/{id}', [AdminFlowSettingController::class, 'edit'])->name('edit');
                    Route::post('update/{id}', [AdminFlowSettingController::class, 'update'])->name('update');
                });

                // Company
                Route::prefix('company')->name('company.')->group(function () {
                    Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
                    Route::get('add', [AdminCompanyController::class, 'create'])->name('create');
                    Route::post('add', [AdminCompanyController::class, 'store'])->name('store');
                    Route::get('edit/{company}', [AdminCompanyController::class, 'show'])->name('show');
                    Route::post('edit/{id}', [AdminCompanyController::class, 'update'])->name('update');
                    Route::post('delete/{id}', [AdminCompanyController::class, 'delete'])->name('delete');
                });

                // Budget
                Route::prefix('budget')->name('budget.')->group(function () {
                    Route::get('edit', [AdminBudgetController::class, 'show'])->name('show');
                    Route::post('edit', [AdminBudgetController::class, 'update'])->name('update');
                });

                // Status
                Route::get('status/{status}', [AdminStatusController::class, 'index'])->name('status');
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
                Route::get('dashboard/{id}', [UserDashboardController::class, 'index'])->name('dashboard');
                // Confirm
                Route::get('confirm', [ConfirmController::class, 'index'])->name('confirm');
                // DRAFT
                Route::get('draft', [UserDraftController::class, 'index'])->name('draft');
                Route::post('draft/{id}', [UserDraftController::class, 'delete'])->name('draft.delete');
                // Status
                Route::get('status/{status}', [UserStatusController::class, 'index'])->name('status');
                // Create Company
                Route::get('company/add', [UserCompanyController::class, 'create'])->name('company.create');
                Route::post('company/add', [UserCompanyController::class, 'store'])->name('company.store');
                // Form list
                Route::get('form', [FormListController::class, 'index'])->name('form.index');
                // Leave Application
                Route::prefix('leave')->name('leave.')->group(function () {
                    Route::get('add', [LeaveApplicationController::class, 'create'])->name('create');
                    Route::post('add', [LeaveApplicationController::class, 'store'])->name('store');
                    Route::get('edit/{id}', [LeaveApplicationController::class, 'show'])->name('show');
                    Route::post('edit/{id}', [LeaveApplicationController::class, 'update'])->name('update');
                    Route::get('preview/{id}', [LeaveApplicationController::class, 'preview'])->name('preview');
                    Route::post('preview/{id}', [LeaveApplicationController::class, 'previewPdf'])->name('preview.pdf');
                });
                // Business Trip Application
                Route::prefix('business')->name('business.')->group(function () {
                    Route::get('add', [BusinessTripController::class, 'create'])->name('create');
                    Route::post('add', [BusinessTripController::class, 'store'])->name('store');
                    Route::get('edit/{id}', [BusinessTripController::class, 'show'])->name('show');
                    Route::post('edit/{id}', [BusinessTripController::class, 'update'])->name('update');
                    Route::get('preview/{id}', [BusinessTripController::class, 'preview'])->name('preview');
                    Route::post('preview/{id}', [BusinessTripController::class, 'previewPdf'])->name('preview.pdf');
                });
                // Entertainment Application
                Route::prefix('entertainment')->name('entertainment.')->group(function () {
                    Route::get('add', [EntertainmentController::class, 'create'])->name('create');
                    Route::post('add', [EntertainmentController::class, 'store'])->name('store');
                    Route::get('edit/{id}', [EntertainmentController::class, 'show'])->name('show');
                    Route::post('edit/{id}', [EntertainmentController::class, 'update'])->name('update');
                    Route::get('preview/{id}', [EntertainmentController::class, 'preview'])->name('preview');
                    Route::post('preview/{id}', [EntertainmentController::class, 'previewPdf'])->name('preview.pdf');
                });
                // Approval
                Route::prefix('approval')->name('approval.')->group(function() {
                    Route::get('list', [ApprovalController::class, 'index'])->name('index');
                    Route::get('detail/{id}', [ApprovalController::class, 'show'])->name('show');
                    Route::post('detail/{id}', [ApprovalController::class, 'update'])->name('update');
                });
            });
        });
    // });

    /**----------------------------------------*
     * Common Routes
     *-----------------------------------------*/
    // Change pass
    Route::get('/changepass', [UserChangePassController::class, 'show'])->name('changepass.show');
    Route::post('/changepass', [UserChangePassController::class, 'update'])->name('changepass.update');
});
