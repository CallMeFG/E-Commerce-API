<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Group V1 untuk API Versioning (Poin C)
Route::prefix('v1')->group(function () {

    // Public Routes
    // throttle:api -> Membatasi request (default 60 req/menit)
    // Kita bisa buat custom throttle khusus login nanti jika perlu lebih ketat.
    Route::middleware('throttle:api')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login'])->name('login');

        Route::get('/products',[ProductController::class,'index']);
        Route::get('/products/{id}',[ProductController::class,'show']);
    });

    // Protected Routes (Butuh Token)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Contoh endpoint untuk cek user yang sedang login
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/shops', [ShopController::class, 'store']);
        Route::post('/products', [ProductController::class, 'store']);

        Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('checkout');
    });

});