<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;



Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::put('/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/destroy/{id}', [CategoryController::class, 'softDelete']);
    Route::delete('/destroy/{id}/force', [CategoryController::class, 'forceDelete']);
    Route::get('/order-up/{id}', [CategoryController::class, 'orderUp']);
    Route::get('/order-down/{id}', [CategoryController::class, 'orderDown']);
});
