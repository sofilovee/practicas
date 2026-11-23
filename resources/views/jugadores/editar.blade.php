@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Editar jugador</h4>

                <form method="POST" action="{{ route('jugadores.update', $jugador) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $jugador->name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $jugador->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña (déjala vacía para no cambiarla)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <button class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('jugadores.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection