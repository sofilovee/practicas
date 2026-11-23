@extends('Nav.navbar')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Usuarios del sistema</h4>
    <div>
        <a href="{{ route('partidas.index') }}" class="btn btn-secondary">Volver a partidas</a>
        <a href="{{ route('jugadores.crear') }}" class="btn btn-primary">Nuevo jugador</a>
    </div>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->role }}</td>
                <td>
                    {{-- Solo los jugadores se pueden editar y eliminar desde aquí --}}
                    @if ($usuario->role === 'jugador')
                        <a href="{{ route('jugadores.editar', $usuario) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('jugadores.destroy', $usuario) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Se eliminará el jugador y sus partidas. ¿Continuar?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection