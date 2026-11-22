@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Mi información académica</h4>

                @if ($alumno)
                    <p><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Faltas:</strong> {{ $alumno->faltas }}</p>
                    <p><strong>Calificación:</strong> {{ $alumno->calificacion }}</p>
                    <p><strong>Equivalencia:</strong> {{ $alumno->equivalencia }}</p>
                @else
                    <p>Todavía no tienes información académica registrada.</p>
                @endif

                <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection