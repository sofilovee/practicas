@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Detalle del usuario</h4>
                <p><strong>Nombre:</strong> {{ $usuario->name }}</p>
                <p><strong>Correo:</strong> {{ $usuario->email }}</p>
                <p><strong>Rol:</strong> {{ $usuario->role }}</p>
                <p><strong>Registrado:</strong> {{ $usuario->created_at }}</p>

                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection