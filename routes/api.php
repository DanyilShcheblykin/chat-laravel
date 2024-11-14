<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

require base_path("routes/auth.php");

    Route::get('/', function () {
        dd('test');
    });
