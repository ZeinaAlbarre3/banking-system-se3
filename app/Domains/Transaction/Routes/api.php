<?php

use App\Domains\Transaction\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/', [TransactionController::class, 'store'])->middleware('permission:create-transaction');
    Route::get('/', [TransactionController::class, 'index']);//->middleware('permission:view-transactions');
    Route::get('/{transaction}', [TransactionController::class, 'show']);//->middleware('permission:view-transaction');
    Route::patch('/{transaction}/approve', [TransactionController::class, 'approve'])->middleware('permission:approve-transaction');
    Route::patch('/{transaction}/reject', [TransactionController::class, 'reject'])->middleware('permission:reject-transaction');
});
