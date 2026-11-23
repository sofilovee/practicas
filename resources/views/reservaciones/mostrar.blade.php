@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Detalle de la reservación</h4>
                <p><strong>Usuario:</strong> {{ $reservacion->user->name }}</p>
                <p><strong>Equipo:</strong> {{ $reservacion->equipo->nombre }}</p>
                <p><strong>Estado:</strong> {{ $reservacion->estado }}</p>
                <p><strong>Fecha:</strong> {{ $reservacion->created_at->format('d/m/Y H:i') }}</p>

                <a href="{{ route('reservaciones.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection