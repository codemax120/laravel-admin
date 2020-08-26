<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

Route::group([
    'prefix' => 'v1'
], function () {

    Route::post('login', 'AuthController@login');

    Route::group([

        'middleware' => ['jwt.auth'],
        'prefix' => 'auth'

    ], function () {

        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

        Route::get('users', 'UserController@index');
    });
});

