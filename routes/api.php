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

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/{id?}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::post('/update', [CategoryController::class, 'update']);
        Route::delete('/', [CategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'entrances'], function () {
        Route::get('/{created_at}/{id?}', [EntraceController::class, 'show']);
        Route::post('/', [EntraceController::class, 'store']);
        Route::put('/', [EntraceController::class, 'update']);
        Route::delete('/', [EntraceController::class, 'destroy']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::put('update', [UserController::class, 'update']);
        Route::delete('destroy', [UserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'wallets'], function () {
        Route::get('/{id?}', [WalletController::class, 'show']);
        Route::post('/', [WalletController::class, 'store']);
        Route::put('/', [WalletController::class, 'update']);
        Route::delete('/', [WalletController::class, 'destroy']);
    });
});
