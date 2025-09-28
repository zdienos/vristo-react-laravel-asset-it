<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::post('/register', [AuthController::class, 'register'])
//                 ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth');

// Route::get('/auth/sigin', [AppController::class, 'index']);


Route::get('/auth/signin', function () {
    return view('app'); // atau redirect ke React index.html
})->name('login');


Route::get('/{any}', [AppController::class, 'index'])
    ->middleware('auth:sanctum')
    ->where('any', '.*');

// Route::get('/{any}', fn(Request $request) => $request->user())->middleware('auth:sanctum');


// Route::get('/{any}', [AppController::class, 'index'])->where('any', '.*');
