<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;

// Fix CSRF for API
Route::middleware('api')->group(function () {
    // PUBLIC
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/menu',      [MenuItemController::class, 'index']);
    Route::get('/menu/{id}', [MenuItemController::class, 'show']);

    // PROTECTED
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',         [AuthController::class, 'logout']);
        Route::get('/profile',         [AuthController::class, 'profile']);
        Route::put('/profile',         [AuthController::class, 'updateProfile']);
        Route::post('/orders',         [OrderController::class, 'store']);
        Route::get('/orders/my',       [OrderController::class, 'userOrders']);
        Route::post('/reservations',   [ReservationController::class, 'store']);
        Route::get('/reservations/my', [ReservationController::class, 'userReservations']);

        // ADMIN
        Route::get('/admin/orders',                   [OrderController::class, 'index']);
        Route::put('/admin/orders/{id}/status',       [OrderController::class, 'updateStatus']);
        Route::get('/admin/reservations',             [ReservationController::class, 'index']);
        Route::put('/admin/reservations/{id}/status', [ReservationController::class, 'updateStatus']);
        Route::post('/admin/menu',                    [MenuItemController::class, 'store']);
        Route::put('/admin/menu/{id}',                [MenuItemController::class, 'update']);
        Route::delete('/admin/menu/{id}',             [MenuItemController::class, 'destroy']);
    });
});