<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;

Route::get('version', function () {
    return env('APP_VERSION');
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('register', [JWTAuthController::class, 'register']);
    Route::post('login', [JWTAuthController::class, 'login']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
    Route::post('refresh', [JWTAuthController::class, 'refresh']);
    Route::post('profile', [JWTAuthController::class, 'profile']);
});
