<?php

use Illuminate\Support\Facades\Route;

// Auth
Route::prefix('auth')->group(function () {
    // Login & Register
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    // Authenticated User
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

// Admin
Route::middleware(['isAdmin'])->group(function () {
    // Brands
    Route::get('/brands', 'AdminController@getBrands');
    
    // Categories
    Route::get('/categories', 'AdminController@getCategories');

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/{id}', 'AdminController@getProduct');
        Route::post('/create', 'AdminController@addProduct');
        Route::post('/edit/{id}', 'AdminController@updateProduct');
        Route::delete('/delete/{id}', 'AdminController@deleteProduct');
    });
});

// Customer
Route::middleware(['auth:api'])->group(function () {
    // Products
    Route::get('/products', 'CustomerController@getProducts');

    // Cart
    Route::prefix('/cart')->group(function () {
        Route::get('/', 'CustomerController@getCartProducts');
        Route::post('/add', 'CustomerController@addCartProduct');
        Route::delete('/delete/{id}', 'CustomerController@deleteCartProduct');
    });
});
