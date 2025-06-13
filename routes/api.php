<?php

use App\Http\Controllers\KeyController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('regions', [RegionController::class, 'index']);
    Route::get('prices', [PriceController::class, 'index']);
    
    Route::controller(KeyController::class)->group(function () {
        Route::get('keys', 'index');
        Route::get('keys/{keyId}', 'show');
        Route::post('checkout', 'buy');
    });
});
