@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Nuevo equipo</h4>

                <form method="POST" action="{{ route('equipos.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                    </div>

                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection