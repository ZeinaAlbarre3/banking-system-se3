<?php

use App\Domains\Transaction\Http\Controllers\ScheduledTransactionController;
use App\Domains\Transaction\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('scheduled-transactions')->group(function () {
        Route::get('/', [ScheduledTransactionController::class, 'index']);//->middleware('permission:view-my-scheduled-transactions');
        Route::post('/', [ScheduledTransactionController::class, 'store']);//->middleware('permission:create-scheduled-transaction');
        Route::get('/{scheduledTransaction}', [ScheduledTransactionController::class, 'show']);//->middleware('permission:view-scheduled-transactions');
        Route::patch('/{scheduledTransaction}/toggle', [ScheduledTransactionController::class, 'toggle']);//->middleware('permission:update-scheduled-transaction');
        Route::delete('/{scheduledTransaction}', [ScheduledTransactionController::class, 'destroy']);//->middleware('permission:delete-scheduled-transaction');
    });

    Route::prefix('transactions')->group(function () {
        Route::post('/', [TransactionController::class, 'store'])->middleware('permission:create-transaction');
        Route::get('/', [TransactionController::class, 'index'])->middleware('permission:view-transactions');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->middleware('permission:view-transaction');
        Route::patch('/{transaction}/approve', [TransactionController::class, 'approve']);//->middleware('permission:approve-transaction');
        Route::patch('/{transaction}/reject', [TransactionController::class, 'reject']);//->middleware('permission:reject-transaction');
    });
});
