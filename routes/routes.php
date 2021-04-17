<?php

use FastDog\Adm\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;

Route::group([
    'middleware' => ['api', 'auth'],
    'prefix' => 'api',
], function () {

    Route::get('user/info', [AdminController::class, 'me']);
    Route::get('user/nav', [AdminController::class, 'nav']);
    Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show']);
    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);

    Route::get('role', [AdminController::class, 'role']);


    Route::get('/resource', [AdminController::class, 'resource']);
    Route::get('/resource/form', [AdminController::class, 'resourceForm']);
});
