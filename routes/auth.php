<?php

use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->group(function () {
        Route::post('/register-student', [AuthController::class, 'registerUser']);
        Route::post('/login-student', [AuthController::class, 'login']);
        Route::post('/verify-student', [AuthController::class, 'verifyUser']);

    });
