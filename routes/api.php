<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntraceController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;

Route::post('login', [JWTAuthController::class, 'login']);
Route::post('register', [JWTAuthController::class, 'register']);

Route::group(['middleware' => ['api', 'guest']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('refresh', [JWTAuthController::class, 'refresh']);
        Route::post('profile', [JWTAuthController::class, 'profile']);
        Route::delete('logout', [JWTAuthController::class, 'logout']);
    });

    Route::group(['middleware' => 'admin.allow'], function () {
        Route::apiResources(['wallets-types' => App\Http\Controllers\WalletTypeController::class]);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/{uuid?}', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'create']);
        Route::put('/{uuid}', [CategoryController::class, 'update']);
        Route::delete('/{uuid}', [CategoryController::class, 'delete']);
    });

    Route::group(['prefix' => 'entraces'], function () {
        Route::get('/show/{uuid}', [EntraceController::class, 'show']);
        Route::get('/{walletUuid}/{categoryUUid?}', [EntraceController::class, 'index']);
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
        Route::post('/', [WalletController::class, 'create']);
        Route::put('/{uuid}', [WalletController::class, 'update']);
        Route::delete('/{uuid}', [WalletController::class, 'delete']);
    });
});
