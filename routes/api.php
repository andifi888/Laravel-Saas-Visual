<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    Route::get('/dashboard/overview', [DashboardApiController::class, 'overview'])->name('api.dashboard.overview');
    Route::get('/dashboard/charts', [DashboardApiController::class, 'charts'])->name('api.dashboard.charts');

    Route::apiResource('products', ProductApiController::class)->names([
        'index' => 'api.products.index',
        'store' => 'api.products.store',
        'show' => 'api.products.show',
        'update' => 'api.products.update',
        'destroy' => 'api.products.destroy',
    ]);
    Route::apiResource('categories', CategoryApiController::class)->names([
        'index' => 'api.categories.index',
        'store' => 'api.categories.store',
        'show' => 'api.categories.show',
        'update' => 'api.categories.update',
        'destroy' => 'api.categories.destroy',
    ]);
    Route::apiResource('customers', CustomerApiController::class)->names([
        'index' => 'api.customers.index',
        'store' => 'api.customers.store',
        'show' => 'api.customers.show',
        'update' => 'api.customers.update',
        'destroy' => 'api.customers.destroy',
    ]);
    Route::apiResource('orders', OrderApiController::class)->names([
        'index' => 'api.orders.index',
        'store' => 'api.orders.store',
        'show' => 'api.orders.show',
        'update' => 'api.orders.update',
        'destroy' => 'api.orders.destroy',
    ]);

    Route::post('/orders/{order}/status', [OrderApiController::class, 'updateStatus'])->name('api.orders.update-status');
    Route::post('/orders/{order}/cancel', [OrderApiController::class, 'cancel'])->name('api.orders.cancel');
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
})->name('api.health');
