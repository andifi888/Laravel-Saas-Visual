<?php

use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\CategoryApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    Route::get('/dashboard/overview', [DashboardApiController::class, 'overview']);
    Route::get('/dashboard/charts', [DashboardApiController::class, 'charts']);
    
    Route::apiResource('products', ProductApiController::class);
    Route::apiResource('categories', CategoryApiController::class);
    Route::apiResource('customers', CustomerApiController::class);
    Route::apiResource('orders', OrderApiController::class);
    
    Route::post('/orders/{order}/status', [OrderApiController::class, 'updateStatus']);
    Route::post('/orders/{order}/cancel', [OrderApiController::class, 'cancel']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});
