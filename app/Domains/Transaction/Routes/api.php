<?php

use App\Domains\Transaction\Http\Controllers\ScheduledTransactionController;
use App\Domains\Transaction\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('scheduled-transactions')->group(function () {
        Route::get('/', [ScheduledTransactionController::class, 'index']);
        Route::post('/', [ScheduledTransactionController::class, 'store']);
        Route::get('/{scheduledTransaction}', [ScheduledTransactionController::class, 'show']);
        Route::patch('/{scheduledTransaction}/toggle', [ScheduledTransactionController::class, 'toggle']);
        Route::delete('/{scheduledTransaction}', [ScheduledTransactionController::class, 'destroy']);
    });

    Route::prefix('transactions')->group(function () {
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/{transaction}', [TransactionController::class, 'show']);
        Route::post('/{transaction}/approve', [TransactionController::class, 'approve']);
        Route::post('/{transaction}/reject', [TransactionController::class, 'reject']);
    });
});
