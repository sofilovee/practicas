@extends('Nav.navbar')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>
        @if (auth()->user()->role === 'admin')
            Todas las reservaciones
        @else
            Mis reservaciones
        @endif
    </h4>
    <div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
        <a href="{{ route('equipos.index') }}" class="btn btn-info">Equipos</a>
    </div>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>#</th>
            @if (auth()->user()->role === 'admin')
                <th>Usuario</th>
            @endif
            <th>Equipo</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reservaciones as $reservacion)
            <tr>
                <td>{{ $reservacion->id }}</td>
                @if (auth()->user()->role === 'admin')
                    <td>{{ $reservacion->user->name }}</td>
                @endif
                <td>{{ $reservacion->equipo->nombre }}</td>
                <td>
                    @if ($reservacion->estado === 'activa')
                        <span class="badge bg-success">Activa</span>
                    @else
                        <span class="badge bg-secondary">Cancelada</span>
                    @endif
                </td>
                <td>{{ $reservacion->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('reservaciones.mostrar', $reservacion) }}" class="btn btn-info btn-sm">Ver</a>

                    @if ($reservacion->estado === 'activa')
                        <form action="{{ route('reservaciones.cancelar', $reservacion) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('¿Cancelar esta reservación?')">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-danger btn-sm">Cancelar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No hay reservaciones.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection