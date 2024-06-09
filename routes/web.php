<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});
Route::get('/chamados', function () {
    return view('chamados');
});
Route::get('/chamado/{chamadoId}', function ($chamadoId) {
    return view('chamado', compact('chamadoId'));
});

Route::get('/register', function () {
    return view('register');
});
