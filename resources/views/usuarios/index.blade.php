@extends('Nav.navbar')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Usuarios</h4>
    <div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
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
                    <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-info btn-sm">Ver</a>

                    @if (auth()->user()->role === 'admin' || $usuario->role !== 'admin')
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">Editar</a>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('¿Deshabilitar este usuario?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-secondary btn-sm">Deshabilitar</button>
                        </form>

                        <form action="{{ route('usuarios.forceDelete', $usuario->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Se eliminará para siempre. ¿Continuar?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar permanente</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if (auth()->user()->role === 'admin' && $eliminados && $eliminados->count() > 0)
    <h5 class="mt-4">Usuarios deshabilitados</h5>
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
            @foreach ($eliminados as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->role }}</td>
                    <td>
                        <form action="{{ route('usuarios.restore', $usuario->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success btn-sm">Restaurar</button>
                        </form>

                        <form action="{{ route('usuarios.forceDelete', $usuario->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Se eliminará para siempre. ¿Continuar?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar permanente</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection