@extends('Nav.navbar')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Equipos de cómputo</h4>
    <div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
        <a href="{{ route('reservaciones.index') }}" class="btn btn-info">Reservaciones</a>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('equipos.crear') }}" class="btn btn-primary">Nuevo equipo</a>
        @endif
    </div>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($equipos as $equipo)
            <tr>
                <td>{{ $equipo->nombre }}</td>
                <td>{{ $equipo->descripcion }}</td>
                <td>
                    @if ($equipo->estado === 'disponible')
                        <span class="badge bg-success">Disponible</span>
                    @else
                        <span class="badge bg-danger">Reservado</span>
                    @endif
                </td>
                <td>
                    @if (auth()->user()->role === 'estudiante')
                        <form action="{{ route('equipos.reservar', $equipo) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm">Reservar</button>
                        </form>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('equipos.editar', $equipo) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar este equipo?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No hay equipos disponibles.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection