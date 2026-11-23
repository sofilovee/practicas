@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Editar equipo</h4>

                <form method="POST" action="{{ route('equipos.update', $equipo) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $equipo->nombre) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $equipo->descripcion) }}</textarea>
                    </div>

                    <button class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection