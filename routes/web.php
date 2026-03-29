<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    RegisteredUserController,
    PasswordResetLinkController,
    PasswordController,
    VerifyEmailController,
    EmailVerificationPromptController,
    ConfirmablePasswordController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify');
    Route::post('email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');
    
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    
    Route::middleware(['tenant'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/charts', [DashboardController::class, 'charts'])->name('dashboard.charts');
        Route::get('dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
        
        Route::resource('products', ProductController::class)->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'show' => 'products.show',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);
        
        Route::resource('categories', CategoryController::class)->names([
            'index' => 'categories.index',
            'create' => 'categories.create',
            'store' => 'categories.store',
            'show' => 'categories.show',
            'edit' => 'categories.edit',
            'update' => 'categories.update',
            'destroy' => 'categories.destroy',
        ]);
        
        Route::resource('customers', CustomerController::class)->names([
            'index' => 'customers.index',
            'create' => 'customers.create',
            'store' => 'customers.store',
            'show' => 'customers.show',
            'edit' => 'customers.edit',
            'update' => 'customers.update',
            'destroy' => 'customers.destroy',
        ]);
        
        Route::resource('orders', OrderController::class)->names([
            'index' => 'orders.index',
            'create' => 'orders.create',
            'store' => 'orders.store',
            'show' => 'orders.show',
            'edit' => 'orders.edit',
            'update' => 'orders.update',
            'destroy' => 'orders.destroy',
        ]);
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        
        Route::middleware(['role:Admin|Manager'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
            
            Route::resource('users', UserController::class);
            Route::post('users/{user}/role', [UserController::class, 'assignRole'])->name('users.assign-role');
            Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            
            Route::resource('tenants', TenantController::class);
            Route::post('tenants/{tenant}/toggle', [TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');
            
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class)->only(['index', 'create', 'store', 'destroy']);
        });
    });
});
