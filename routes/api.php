<?php

use App\Http\Controllers\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/customer/login', [CustomerAuthController::class, 'login']);

Route::middleware(['auth.jwt', 'role:customer'])->group(function () {
    Route::get('/me', [CustomerAuthController::class, 'me']);
    Route::put('/update-profile', [CustomerAuthController::class, 'updateProfile']);
});
