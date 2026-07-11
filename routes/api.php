<?php

use App\Http\Controllers\Api\Admin\CoinController;
use App\Http\Controllers\Api\Admin\CurrencyController;
use App\Http\Controllers\Api\Admin\DrinkController;
use App\Http\Controllers\Api\VendingController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    Route::apiResource('drinks', DrinkController::class);
    Route::apiResource('coins', CoinController::class);

    Route::post('/restart', [VendingController::class, 'restart']);
    Route::get('/display', [VendingController::class, 'log']);
    Route::get('/currency', [CurrencyController::class, 'index']);
    Route::put('/currency/{currency}', [CurrencyController::class, 'update']);
});

Route::prefix('vending')->group(function () {
    Route::get('/drinks', [VendingController::class, 'viewDrinks']);
    Route::get('/coins', [VendingController::class, 'coinsList']);
    Route::post('/coins', [VendingController::class, 'putCoin']);
    Route::post('/buy', [VendingController::class, 'buyDrink']);
    Route::get('/change', [VendingController::class, 'getCoins']);
    Route::get('/amount', [VendingController::class, 'viewAmount']);
    Route::get('/balance', [VendingController::class, 'viewBalance']);
    Route::get('/display', [VendingController::class, 'display']);
    Route::get('/state', [VendingController::class, 'state']);
});
