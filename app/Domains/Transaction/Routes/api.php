<?php

use App\Domains\Transaction\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/transactions', [TransactionController::class, 'store'])->middleware('permission:create-transaction');
    // Route::get('/transactions', [TransactionController::class, 'index'])->middleware('permission:view-transaction');
    // Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->middleware('permission:view-transaction');
});
