<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ClientOrdersController;

Route::post('auth/token',  [TokenController::class, 'issue']);
Route::post('auth/logout', [TokenController::class, 'revoke'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    //Orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::get('clients/{id}/orders', [ClientOrdersController::class, 'index']);

    //Clients & Users
    Route::post('clients', [ClientController::class, 'store']);
    Route::post('users', [UserController::class, 'store']);
});
