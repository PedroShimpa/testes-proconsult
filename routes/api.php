<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});

Route::group([
    'prefix' => 'chamados'
], function ($router) {
    Route::post('', 'App\Http\Controllers\ChamadosController@store');
    Route::get('', 'App\Http\Controllers\ChamadosController@index');
    Route::get('/{id}', 'App\Http\Controllers\ChamadosController@getById')->where('id', '[0-9]+');;
    Route::post('/reply/{id}', 'App\Http\Controllers\ChamadosController@replyChamado')->where('id', '[0-9]+');;
    Route::put('/finish/{id}', 'App\Http\Controllers\ChamadosController@finishChamado')->where('id', '[0-9]+');;
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('register', 'App\Http\Controllers\UsersController@store');
});
