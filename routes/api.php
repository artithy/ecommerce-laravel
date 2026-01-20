<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/customer/login', [CustomerAuthController::class, 'login']);

Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login', [AdminController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'deleteCategory']);
Route::get('/products', [ProductController::class, 'getAllProducts']);
Route::get('/products/{id}', [ProductController::class, 'getSingleProduct']);
Route::get('/products-by-category', [ProductController::class, 'getProductsByCategory']);
Route::middleware(['auth.jwt', 'role:customer'])->group(function () {
    Route::get('/me', [CustomerAuthController::class, 'me']);
    Route::put('/update-profile', [CustomerAuthController::class, 'updateProfile']);

    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/add/cart', [CartController::class, 'addToCart']);
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart']);
    Route::delete('/cart/delete/{id}', [CartController::class, 'deleteCart']);

    Route::get('/orders', [OrderController::class, 'getCustomerOrders']);
});
Route::middleware(['auth.jwt', 'role:admin'])->group(function () {
    Route::post('/admin/categories', [CategoryController::class, 'addCategory']);
    Route::put('/admin/categories/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/admin/delete/{id}', [CategoryController::class, 'deleteCategory']);

    Route::post('/admin/products', [ProductController::class, 'createProduct']);
    Route::put('/admin/products/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [ProductController::class, 'deleteProduct']);

    Route::get('/admin/order', [OrderController::class, 'getAllOrder']);
    Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateOrderStatus']);
    Route::get('/admin/orders/recent', [OrderController::class, 'getRecentOrders']);
});
