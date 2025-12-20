<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Dashboard\Controllers\AdminDashboardController;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
          //  ->middleware('permission:view-dashboard');
    });






