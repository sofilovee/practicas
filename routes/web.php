<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.enviar');

Route::get('/verificacion', [AuthController::class, 'mostrarVerificacion'])
    ->name('verificacion.mostrar')
    ->middleware('signed');
    
Route::post('/verificacion', [AuthController::class, 'verificarCodigo'])
    ->name('verificacion.verificar')
    ->middleware('signed');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');
});