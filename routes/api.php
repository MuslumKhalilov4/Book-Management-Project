<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
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

    Route::prefix('author')->group(function () {
        Route::get('/', [AuthorController::class, 'index']);
        Route::get('/{id}', [AuthorController::class, 'show']);
        Route::post('/store', [AuthorController::class, 'store']);
        Route::put('/update/{id}', [AuthorController::class, 'update']);
        Route::delete('/destroy/{id}', [AuthorController::class, 'softDelete']);
        Route::delete('/destroy/{id}/force', [AuthorController::class, 'forceDelete']);
        Route::get('/order-up/{id}', [AuthorController::class, 'orderUp']);
        Route::get('/order-down/{id}', [AuthorController::class, 'orderDown']);
    });

    Route::prefix('book')->group(function () {
        Route::get('/', [BookController::class, 'index']);
        Route::get('/{id}', [BookController::class, 'show']);
        Route::post('/store', [BookController::class, 'store']);
        Route::put('/update/{id}', [BookController::class, 'update']);
        Route::delete('/destroy/{id}', [BookController::class, 'softDelete']);
        Route::delete('/destroy/{id}/force', [BookController::class, 'forceDelete']);
        Route::get('/order-up/{id}', [BookController::class, 'orderUp']);
        Route::get('/order-down/{id}', [BookController::class, 'orderDown']);
    });
});

Route::post('/register', [AuthController::class, 'register'])->middleware('alreadyLoggedIn');
Route::post('/login', [AuthController::class, 'login'])->middleware('alreadyLoggedIn');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
