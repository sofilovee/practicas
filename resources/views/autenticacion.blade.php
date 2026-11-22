@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Práctica 1.1: Autenticación por rol</h4>

                <p><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Correo:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Rol:</strong> {{ auth()->user()->role }}</p>

                @if (auth()->user()->role === 'admin')
                    <p>Iniciaste sesión con verificación en tres etapas (correo, contraseña y código).</p>
                @else
                    <p>Iniciaste sesión con correo y contraseña.</p>
                @endif

                <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection