@extends('Nav.navbar')

@section('contenido')
@php
    $practicas = [
        ['1.1', 'Autenticacion por rol', 'Login con codigo de verificacion para el administrador.', 'autenticacion', ['admin', 'empleado', 'profesor', 'alumno', 'estudiante', 'jugador']],
        ['1.2', 'CRUD de usuarios', 'Usuarios con permisos por rol y Soft Delete.', 'usuarios.index', ['admin', 'empleado']],
        ['1.3', 'Sistema academico', 'Alumnos, faltas y calificaciones.', 'alumnos.index', ['profesor', 'alumno']],
        ['1.4', 'Gestion de productos', 'Paginacion, filtros y ordenamiento.', 'productos.index', ['admin', 'empleado']],
        ['1.5', 'Reservacion de equipos', 'Sesiones independientes y reservaciones.', 'equipos.index', ['admin', 'estudiante']],
        ['1.6', 'Juego del gato', 'Partidas multijugador.', 'partidas.index', ['admin', 'jugador']],
    ];
@endphp

<div class="mb-4">
    <h4>Bienvenido, {{ auth()->user()->name }}</h4>
    <p>Rol: <strong>{{ auth()->user()->role }}</strong></p>
</div>

<div class="row">
    @foreach ($practicas as $p)
        @php
            $tieneAcceso = in_array(auth()->user()->role, $p[4]);
            $existe = Route::has($p[3]);
        @endphp

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5>Práctica {{ $p[0] }}</h5>
                    <h6 class="text-muted">{{ $p[1] }}</h6>
                    <p class="flex-grow-1">{{ $p[2] }}</p>

                    @if ($tieneAcceso && $existe)
                        <a href="{{ route($p[3]) }}" class="btn btn-primary">Entrar</a>
                    @elseif ($tieneAcceso)
                        <button class="btn btn-secondary" disabled>Proximamente</button>
                    @else
                        <button class="btn btn-outline-secondary" disabled>Sin acceso</button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection