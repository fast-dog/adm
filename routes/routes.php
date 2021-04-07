<?php

use FastDog\Adm\Resources\User\Forms\Identity;
use FastDog\Adm\Resources\User\UserResource;
use Illuminate\Support\Facades\Route;


//Route::group([
//    'prefix' => config('adm.admin_path', 'admin'),
//    'middleware' => ['web'],
//], function () {
//    Route::get('/', '\FastDog\Adm\Http\Controllers\AdminController@getIndex');
//    Route::get('/menu', '\FastDog\Adm\Http\Controllers\AdminController@getMenu');
//    Route::get('/routes', '\FastDog\Adm\Http\Controllers\AdminController@getInterfaceRoute');
//});


Route::get('/resources/{id?}', function ($id, UserResource $userResource, Identity $identity) {

    $userResource->setForm($identity);

})->where('id', '[0-9]+');