@extends('Nav.navbar')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Alumnos</h4>
    <div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
        <a href="{{ route('alumnos.crear') }}" class="btn btn-primary">Nuevo alumno</a>
    </div>
</div>

<form method="GET" action="{{ route('alumnos.index') }}" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="orden" class="form-select">
            <option value="nombre" {{ $orden === 'nombre' ? 'selected' : '' }}>Nombre</option>
            <option value="calificacion" {{ $orden === 'calificacion' ? 'selected' : '' }}>Calificación</option>
            <option value="faltas" {{ $orden === 'faltas' ? 'selected' : '' }}>Faltas</option>
        </select>
    </div>
    <div class="col-auto">
        <select name="direccion" class="form-select">
            <option value="asc" {{ $direccion === 'asc' ? 'selected' : '' }}>Ascendente (A-Z / menor a mayor)</option>
            <option value="desc" {{ $direccion === 'desc' ? 'selected' : '' }}>Descendente (Z-A / mayor a menor)</option>
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-dark">Ordenar</button>
    </div>
</form>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Faltas</th>
            <th>Calificación</th>
            <th>Equivalencia</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($alumnos as $alumno)
            <tr>
                <td>{{ $alumno->nombre }}</td>
                <td>{{ $alumno->faltas }}</td>
                <td>{{ $alumno->calificacion }}</td>
                <td>{{ $alumno->equivalencia }}</td>
                <td>
                    <a href="{{ route('alumnos.editar', $alumno) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('¿Eliminar este alumno?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection