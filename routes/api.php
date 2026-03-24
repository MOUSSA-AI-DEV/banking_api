<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [ProfileController::class, 'show']);
    Route::put('/me', [ProfileController::class, 'update']);
    Route::put('/me/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/me', [ProfileController::class, 'destroy']);

    // Banking Routes
    Route::get('/accounts', [\App\Http\Controllers\AccountController::class, 'index']);
    Route::get('/accounts/{account}', [\App\Http\Controllers\AccountController::class, 'show']);
    Route::post('/accounts', [\App\Http\Controllers\AccountController::class, 'store']);
    Route::post('/accounts/{account}/deposit', [\App\Http\Controllers\TransactionController::class, 'deposit']);
    Route::post('/accounts/{account}/withdraw', [\App\Http\Controllers\TransactionController::class, 'withdraw']);
    Route::post('/transfers', [\App\Http\Controllers\TransferController::class, 'transfer']);
});
