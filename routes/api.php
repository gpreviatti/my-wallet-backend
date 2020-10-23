<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntraceController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;

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

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/{uuid?}', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{uuid}', [CategoryController::class, 'update']);
        Route::delete('/{uuid}', [CategoryController::class, 'delete']);
    });

    Route::group(['prefix' => 'entraces'], function () {
        Route::get('/show/{uuid}', [EntraceController::class, 'show']);
        Route::get('/{walletUuid}/{categoryUUid?}', [EntraceController::class, 'index']);
        Route::post('/', [EntraceController::class, 'store']);
        Route::put('/update/{uuid}', [EntraceController::class, 'update']);
        Route::delete('/delete/{uuid}', [EntraceController::class, 'delete']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::put('/update', [UserController::class, 'update']);
        Route::delete('/delete', [UserController::class, 'delete']);
    });

    Route::group(['prefix' => 'wallets'], function () {
        Route::get('/show/{uuid}', [WalletController::class, 'show']);
        Route::post('/', [WalletController::class, 'store']);
        Route::put('/update/{uuid}', [WalletController::class, 'update']);
        Route::delete('/delete/{uuid}', [WalletController::class, 'delete']);
    });
});
