<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntraceController;

Route::get('version', function () {
    return env('APP_VERSION');
});

Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [JWTAuthController::class, 'register']);
        Route::post('login', [JWTAuthController::class, 'login']);
        Route::post('logout', [JWTAuthController::class, 'logout']);
        Route::post('refresh', [JWTAuthController::class, 'refresh']);
        Route::post('profile', [JWTAuthController::class, 'profile']);
    });

    Route::apiResources(
        [
            'users' => UserController::class,
            'wallets' => WalletController::class,
            'categories' => CategoryController::class,
            'entraces' => EntraceController::class,
        ],
        ['except' => ['index']]
    );
});
