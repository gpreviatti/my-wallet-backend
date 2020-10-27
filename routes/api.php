<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntraceController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletTypeController;

Route::post('login', [JWTAuthController::class, 'login']);
Route::post('register', [JWTAuthController::class, 'register']);

Route::group(['middleware' => ['api', 'guest']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('refresh', [JWTAuthController::class, 'refresh']);
        Route::post('profile', [JWTAuthController::class, 'profile']);
        Route::delete('logout', [JWTAuthController::class, 'logout']);
    });

    /**
     * Routes allowed just for admin user
     */
    Route::group(['middleware' => 'admin.allow'], function () {
        Route::group(['prefix' => 'wallets-types'], function () {
            Route::get('/{uuid?}', [WalletTypeController::class, 'index']);
            Route::post('/', [WalletTypeController::class, 'create']);
            Route::put('/{uuid}', [WalletTypeController::class, 'update']);
            Route::delete('/{uuid}', [WalletTypeController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/{uuid?}', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'create']);
        Route::put('/{uuid}', [CategoryController::class, 'update']);
        Route::delete('/{uuid}', [CategoryController::class, 'delete']);
    });

    Route::group(['prefix' => 'entraces'], function () {
        Route::get('/{uuid}', [EntraceController::class, 'index']);
        Route::post('/', [EntraceController::class, 'create']);
        Route::put('/{uuid}', [EntraceController::class, 'update']);
        Route::delete('/{uuid}', [EntraceController::class, 'delete']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::put('/update', [UserController::class, 'update']);
        Route::delete('/', [UserController::class, 'delete']);
    });

    Route::group(['prefix' => 'wallets'], function () {
        Route::get('/{uuid}', [WalletController::class, 'index']);
        Route::get('/entraces/{uuid}', [WalletController::class, 'entraces']);
        Route::post('/', [WalletController::class, 'create']);
        Route::put('/{uuid}', [WalletController::class, 'update']);
        Route::delete('/{uuid}', [WalletController::class, 'delete']);
    });
});
