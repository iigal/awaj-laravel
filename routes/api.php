<?php

use App\Http\Controllers\MobileController;
use App\Http\Controllers\CategoryController;

Route::post('/register', [MobileController::class, 'register']);
Route::post('/login', [MobileController::class, 'login']);
Route::post('/send-otp', [MobileController::class, 'sendOtp']);
Route::post('/upload-image', [MobileController::class, 'uploadImage']);
Route::get('/public-complaints', [MobileController::class, 'allPublicComplaints']);


Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'apiIndex']); // List all categories (API)
    Route::post('/', [CategoryController::class, 'apiStore']); // Store new category (API)
    Route::get('/{category}', [CategoryController::class, 'apiShow']); // Show specific category details (API)
    Route::put('/{category}', [CategoryController::class, 'apiUpdate']); // Update category (API)
    Route::delete('/{category}', [CategoryController::class, 'apiDestroy']); // Delete category (API)
});

Route::get('/', [CategoryController::class, 'index']);