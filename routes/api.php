<?php

use App\Http\Controllers\Api\Admin\CoinController;
use App\Http\Controllers\Api\Admin\DrinkController;
use App\Http\Controllers\Api\VendingController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('/drinks', [DrinkController::class, 'store']);
    Route::delete('/drinks/{id}', [DrinkController::class, 'destroy']);
    Route::post('/coins', [CoinController::class, 'store']);
    Route::delete('/coins/{id}', [CoinController::class, 'destroy']);
});

Route::prefix('vending')->group(function () {
    Route::get('/drinks', [VendingController::class, 'viewDrinks']);
    Route::get('/coins', [VendingController::class, 'coinsList']);
    Route::post('/coins', [VendingController::class, 'putCoin']);
    Route::post('/buy', [VendingController::class, 'buyDrink']);
    Route::get('/change', [VendingController::class, 'getCoins']);
    Route::get('/amount', [VendingController::class, 'viewAmount']);
    Route::get('/restart', [VendingController::class, 'restart']);
    Route::get('/state', [VendingController::class, 'state']);
    Route::get('/display', [VendingController::class, 'display']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::apiResource('drinks', DrinkController::class);
    Route::apiResource('coins', CoinController::class);
});
