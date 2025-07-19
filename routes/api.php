<?php

use App\Http\Controllers\KeyController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('check.token')->group(function () {
        Route::controller(UserController::class)->prefix('users')->group(function () {
            Route::post('/', 'store');
        });

        Route::controller(KeyController::class)->prefix('keys')->group(function () {
            Route::get('/', 'index');
            Route::get('{keyId}', 'show');
            Route::post('checkout', 'buy');
        });
    });

    Route::get('regions', [RegionController::class, 'index']);
    Route::get('prices', [PriceController::class, 'index']);
});
