<?php

use App\Http\Controllers\ChamadosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
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

Route::get('/chamados/download', [ChamadosController::class, 'downloadFile']);
