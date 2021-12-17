<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([ 'middleware' => 'api', 'prefix' => 'auth' ], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('profile', 'AuthController@profile');

});

Route::group([ 'middleware' => ['auth:api'] ], function ($router) {

    //ProductController
    Route::resource('/products',ProductController::class)->except([
        'create', 'show',
    ]);
});
