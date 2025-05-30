<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductMaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('web');

Route::get('/', function () {
    return response()->json(['message' => 'Welcome'], 200);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products/calculate-amount', [ProductController::class, 'calculateAmount']);

    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/materials', [ProductMaterialController::class, 'index']);
    Route::get('/materials/product/{product_id}', [ProductMaterialController::class, 'productMaterials']);



        Route::post('/product-materials', [ProductMaterialController::class, 'store']);
        Route::put('/product-materials/{id}', [ProductMaterialController::class, 'update']);
        Route::delete('/product-materials/{id}', [ProductMaterialController::class, 'destroy']);

});
