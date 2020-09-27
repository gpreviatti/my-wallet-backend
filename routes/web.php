<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'application' => "My Wallet",
        'version' => env('APP_VERSION')
    ]);
});
