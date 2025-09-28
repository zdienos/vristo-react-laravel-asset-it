<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Rute Autentikasi (Publik)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('brands', BrandController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
});
