<?php

use FastDog\Adm\Http\Controllers\AdminController;
use FastDog\Adm\Http\Controllers\FormController;
use FastDog\Adm\Http\Controllers\NavController;
use FastDog\Adm\Http\Controllers\ResourceController;
use FastDog\Adm\Http\Controllers\SwitchActionController;
use FastDog\Adm\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;

Route::group([
    'middleware' => ['api', 'auth'],
    'prefix' => 'api',
], function () {

    Route::get('/user/info', [AdminController::class, 'info']);
    Route::get('/role', [AdminController::class, 'role']);

    Route::get('/user/nav', [NavController::class, 'nav']);

    Route::get('/user/profile', [ProfileController::class, 'get']);

    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);
    Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy']);

    Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show']);

    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);

    Route::get('/resource', [ResourceController::class, 'resource']);
    Route::get('/resource/form', [ResourceController::class, 'resourceForm']);
    Route::post('/resource/form', [ResourceController::class, 'resourceFormSave']);
    Route::delete('/resource', [ResourceController::class, 'resourceDelete']);
    Route::delete('/resource/assets', [ResourceController::class, 'resourceAssetsDelete']);

    Route::post('/resource/switch-action', [SwitchActionController::class, 'run']);

    Route::get('/resource/fields', [FormController::class, 'fields']);

});
