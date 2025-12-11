<?php

use App\Domains\Account\Http\Controllers\AccountantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [AccountantController::class, 'index'])->middleware('permission:view-accounts');
    Route::get('/my', [AccountantController::class, 'myAccounts'])->middleware('permission:view-my-accounts');
    Route::get('/portfolio/balance', [AccountantController::class, 'portfolioBalance'])->middleware('permission:view-portfolio-balance');
    Route::post('/', [AccountantController::class, 'store'])->middleware('permission:create-account');
    Route::get('/{account}', [AccountantController::class, 'show'])->middleware('permission:view-account');
    Route::put('/{account}', [AccountantController::class, 'update'])->middleware('permission:update-account');
    Route::patch('/{account}/state', [AccountantController::class, 'changeState'])->middleware('permission:update-account');
});
