<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

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

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');

    // Solo administrador
    Route::middleware('admin')->group(function () {
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        Route::delete('/usuarios/{id}/permanente', [UsuarioController::class, 'forceDelete'])->name('usuarios.forceDelete');
        Route::patch('/usuarios/{id}/restaurar', [UsuarioController::class, 'restaurar'])->name('usuarios.restore');
    });
});