@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Editar alumno</h4>

                <form method="POST" action="{{ route('alumnos.update', $alumno) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $alumno->user->name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $alumno->user->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Faltas (0 a 50)</label>
                        <input type="number" name="faltas" class="form-control" value="{{ old('faltas', $alumno->faltas) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Calificación (0 a 10)</label>
                        <input type="number" step="0.1" name="calificacion" class="form-control" value="{{ old('calificacion', $alumno->calificacion) }}">
                    </div>

                    <button class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection