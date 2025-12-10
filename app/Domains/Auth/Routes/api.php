<?php


use App\Domains\Auth\Http\Controllers\AuthController;
use App\Domains\Auth\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-register-otp', [AuthController::class, 'verifyRegisterOtp']);
Route::post('refresh-otp', [AuthController::class, 'refreshCode']);

Route::post('forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('verify-reset-otp', [PasswordResetController::class, 'verifyResetOtp']);
Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});
