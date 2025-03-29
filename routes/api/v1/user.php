<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;

// Orders
Route::resource('orders',OrderController::class);
Route::patch('orders/{id}/change-status', [OrderController::class, 'changeStatus']);

// Payments
Route::resource('payments',PaymentController::class)->only(['store','index']);
Route::get('orders/{orderId}/payments', [PaymentController::class, 'getPaymentsByOrder']);

// Auth
Route::post('auth/logout', [AuthController::class,'logout']);
