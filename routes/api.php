<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JWTAuthController;

Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [JWTAuthController::class, 'register']);
        Route::post('login', [JWTAuthController::class, 'login']);
        Route::post('refresh', [JWTAuthController::class, 'refresh']);
        Route::post('profile', [JWTAuthController::class, 'profile']);
        Route::delete('logout', [JWTAuthController::class, 'logout']);
    });

    Route::group(['middleware' => 'admin.allow'], function () {
        Route::apiResources([
            'wallets-types' => App\Http\Controllers\WalletTypeController::class
        ]);
    });

    Route::apiResources([
        'categories' => App\Http\Controllers\CategoryController::class,
        'entraces' => App\Http\Controllers\EntraceController::class,
        'users' => App\Http\Controllers\UserController::class,
        'wallets' => App\Http\Controllers\WalletController::class,
    ]);
});
