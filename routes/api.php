<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProductsController, AuthController, OrdersController};

Route::prefix('admin')->group(function() {
   Route::prefix('products')->group(function() {
       Route::post('create-image', array(ProductsController::class, 'createProductImage'));
       Route::post('create-product', array(ProductsController::class, 'createProduct'));
       Route::put('edit-product', array(ProductsController::class, 'editProduct'));
       Route::post('create-reference', array(ProductsController::class, 'createReference'));
       Route::put('change-status-product', array(ProductsController::class, 'changeStatusProduct'));
       Route::put('change-status-reference', array(ProductsController::class, 'changeStatusReference'));
       Route::put('change-status-product-image', array(ProductsController::class, 'changeStatusProductImage'));
       Route::get('get-all-products', array(ProductsController::class, 'getAllProducts'));
   });
});

Route::prefix('products')->group(function() {
    Route::get('get-products', array(ProductsController::class, 'getActiveProducts'));
});

Route::prefix('auth')->group(function() {
    Route::post('register', array(AuthController::class, 'createUser'));
    Route::post('login', array(AuthController::class, 'login'));
    Route::post('change-password', array(AuthController::class, 'changePassword'));
    Route::post('change-role', array(AuthController::class, 'changeRole'));
});

Route::prefix('orders')->group(function() {
    Route::post('create-order', array(OrdersController::class, 'createOrder'));
    Route::put('change-status-order', array(OrdersController::class, 'changeStatusOrder'));
});
