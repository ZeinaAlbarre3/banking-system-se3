<?php
use App\Domains\Notification\Http\Controllers\AccountActivityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/account-activities', [AccountActivityController::class, 'store']);
    Route::get('/notifications', [AccountActivityController::class, 'notifications']);
    Route::post('/notifications/{notification}/read', [AccountActivityController::class, 'markAsRead']);
});
