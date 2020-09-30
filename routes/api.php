<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\EntraceController;

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

    Route::group(['prefix' => 'entraces'], function () {
        Route::get('/show/{uuid}', [EntraceController::class, 'show']);
        Route::get('/{walletUuid}/{categoryUUid?}', [EntraceController::class, 'index']);
        Route::post('/', [EntraceController::class, 'store']);
        Route::put('/update/{uuid}', [EntraceController::class, 'update']);
        Route::delete('/destroy/{uuid}', [EntraceController::class, 'destroy']);
    });

    Route::apiResources([
        'categories' => App\Http\Controllers\CategoryController::class,
        'users' => App\Http\Controllers\UserController::class,
        'wallets' => App\Http\Controllers\WalletController::class,
    ]);
});
