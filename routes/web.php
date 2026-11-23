<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\JugadorController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.enviar');
Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registrar'])->name('registro.enviar');

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

    Route::get('/autenticacion', function () {
    return view('autenticacion');
})->name('autenticacion');

    // Admin y empleado consultan, filtran y ordenan 1.4
    Route::get('/productos', [ProductoController::class, 'index'])
        ->middleware('rol:admin,empleado')
        ->name('productos.index');

    Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

    // Solo administrador prctica 1.4
    Route::middleware('admin')->group(function () {
        Route::get('/productos/crear', [ProductoController::class, 'crear'])->name('productos.crear');
        Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('/productos/{producto}/editar', [ProductoController::class, 'editar'])->name('productos.editar');
        Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });

    // Práctica 1.2: usuarios (solo admin y empleado)
    Route::middleware('rol:admin,empleado')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UsuarioController::class, 'crear'])->name('usuarios.crear');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}', [UsuarioController::class, 'mostrar'])->name('usuarios.mostrar');
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'editar'])->name('usuarios.editar');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');

        // Solo administrador
        Route::middleware('admin')->group(function () {
            Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
            Route::delete('/usuarios/{id}/permanente', [UsuarioController::class, 'forceDelete'])->name('usuarios.forceDelete');
            Route::patch('/usuarios/{id}/restaurar', [UsuarioController::class, 'restaurar'])->name('usuarios.restore');
        });
    });

    // Profesor y alumno entran al listado (el alumno solo ve lo suyo) 1.3
    Route::get('/alumnos', [AlumnoController::class, 'index'])
        ->middleware('rol:profesor,alumno')
        ->name('alumnos.index');

    // Solo profesor 1.3    
    Route::middleware('rol:profesor')->group(function () {
        Route::get('/alumnos/crear', [AlumnoController::class, 'crear'])->name('alumnos.crear');
        Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
        Route::get('/alumnos/{alumno}/editar', [AlumnoController::class, 'editar'])->name('alumnos.editar');
        Route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');
        Route::delete('/alumnos/{alumno}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');
    });

        // Práctica 1.5: reservación de equipos
    Route::middleware('rol:admin,estudiante')->group(function () {
        Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
        Route::get('/reservaciones', [ReservacionController::class, 'index'])->name('reservaciones.index');
        Route::get('/reservaciones/{reservacion}', [ReservacionController::class, 'mostrar'])->name('reservaciones.mostrar');
        Route::patch('/reservaciones/{reservacion}/cancelar', [ReservacionController::class, 'cancelar'])->name('reservaciones.cancelar');
    });

    // Solo estudiante
    Route::post('/equipos/{equipo}/reservar', [ReservacionController::class, 'reservar'])
        ->middleware('rol:estudiante')
        ->name('equipos.reservar');

    // Solo administrador
    Route::middleware('admin')->group(function () {
        Route::get('/equipos/crear', [EquipoController::class, 'crear'])->name('equipos.crear');
        Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');
        Route::get('/equipos/{equipo}/editar', [EquipoController::class, 'editar'])->name('equipos.editar');
        Route::put('/equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
        Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    });

        // Practica 1.6: juego del gato
    Route::middleware('rol:admin,jugador')->group(function () {
        Route::get('/partidas', [PartidaController::class, 'index'])->name('partidas.index');
        Route::get('/partidas/{partida}', [PartidaController::class, 'mostrar'])->name('partidas.mostrar');
        Route::get('/partidas/{partida}/estado', [PartidaController::class, 'estado'])->name('partidas.estado');
    });

    // Solo jugador
    Route::middleware('rol:jugador')->group(function () {
        Route::post('/partidas', [PartidaController::class, 'crear'])->name('partidas.crear');
        Route::post('/partidas/{partida}/unirse', [PartidaController::class, 'unirse'])->name('partidas.unirse');
        Route::post('/partidas/{partida}/mover', [PartidaController::class, 'mover'])->name('partidas.mover');
        Route::post('/partidas/{partida}/abandonar', [PartidaController::class, 'abandonar'])->name('partidas.abandonar');
    });

    // Solo administrador
    Route::patch('/partidas/{partida}/cancelar', [PartidaController::class, 'cancelar'])
        ->middleware('admin')
        ->name('partidas.cancelar');

            // Práctica 1.6: administración de jugadores (solo administrador)
    Route::middleware('admin')->group(function () {
        Route::get('/jugadores', [JugadorController::class, 'index'])->name('jugadores.index');
        Route::get('/jugadores/crear', [JugadorController::class, 'crear'])->name('jugadores.crear');
        Route::post('/jugadores', [JugadorController::class, 'store'])->name('jugadores.store');
        Route::get('/jugadores/{jugador}/editar', [JugadorController::class, 'editar'])->name('jugadores.editar');
        Route::put('/jugadores/{jugador}', [JugadorController::class, 'update'])->name('jugadores.update');
        Route::delete('/jugadores/{jugador}', [JugadorController::class, 'destroy'])->name('jugadores.destroy');
    });
    
});