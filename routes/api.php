<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

// Auth
Route::prefix('auth')->group(function () {
    // Login & Register
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    // Password Reset
    Route::post('forgot-password', 'PasswordController@forgotPassword')->middleware('guest');
    Route::post('reset-password', 'PasswordController@resetPassword')->middleware('guest');

    // Email Verification
    Route::get('/email/verify', 'MailVerificationController@emailVerify')->middleware('auth:api')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'MailVerificationController@verifyEmail')->middleware(['auth:api'])->name('verification.verify');
    Route::post('/email/verify/resend', 'MailVerificationController@resendEmailVerify')->middleware(['auth:api'])->name('verification.send');

    // Authenticated User
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

// Admin
Route::middleware(['isAdmin'])->group(function () {
    // Brands
    Route::get('/brands', [BrandController::class, 'index']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/{id}', [ProductController::class, 'find']);
        Route::post('/create', [ProductController::class, 'store']);
        Route::post('/edit/{id}', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'delete']);
    });
});

// Customer
Route::middleware(['auth:api'])->group(function () {
    // Products
    Route::get('/products', [ProductController::class, 'index']);

    // Cart
    Route::prefix('/cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'store']);
        Route::delete('/delete/{id}', [CartController::class, 'delete']);
    });
});
