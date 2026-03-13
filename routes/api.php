<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public routes for services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index']);
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update']);
    Route::post('/settings/logo', [\App\Http\Controllers\SettingController::class, 'uploadLogo']);

    // Services (admin only in real app, but open for demo)
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    // Order Items Progress & Wages
    Route::put('/order-items/{id}/washing', [\App\Http\Controllers\OrderItemController::class, 'updateWashingStatus']);
    Route::put('/order-items/{id}/ironing', [\App\Http\Controllers\OrderItemController::class, 'updateIroningStatus']);
    Route::get('/my-wages', [\App\Http\Controllers\OrderItemController::class, 'myWages']);

    // User Management
    Route::get('/users', [App\Http\Controllers\UserManagementController::class, 'index']);
    Route::put('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'update']);
    Route::delete('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'destroy']);
});
