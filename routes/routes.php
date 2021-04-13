<?php

use FastDog\Adm\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api', 'auth'],
    'prefix' => 'api',
], function () {

    Route::get('user/info', [AdminController::class, 'me']);
    Route::get('user/nav', [AdminController::class, 'nav']);
    Route::get('role', [AdminController::class, 'role']);


    Route::get('/resource/{id?}', function ($id) {

//        $userResource->setForm($identity);

    })->where('id', '[0-9]+');
});
