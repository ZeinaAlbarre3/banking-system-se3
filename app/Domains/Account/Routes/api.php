<?php

use App\Domains\Account\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [AccountController::class, 'index'])->middleware('permission:view-accounts');
    Route::get('/my', [AccountController::class, 'myAccounts'])->middleware('permission:view-my-accounts');
    Route::get('/portfolio/balance', [AccountController::class, 'portfolioBalance'])->middleware('permission:view-portfolio-balance');
    Route::post('/', [AccountController::class, 'store'])->middleware('permission:create-account');
    Route::get('/{account}', [AccountController::class, 'show'])->middleware('permission:view-account');
    Route::put('/{account}', [AccountController::class, 'update'])->middleware('permission:update-account');
    Route::patch('/{account}/state', [AccountController::class, 'changeState'])->middleware('permission:update-account');
    Route::get('/{account}/interest', [AccountController::class, 'interest'])->middleware('permission:view-account');
});
