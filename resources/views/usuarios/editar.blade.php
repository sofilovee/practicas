@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Editar usuario</h4>

                <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña (dejala vacia para no cambiarla)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select name="role" class="form-select">
                                <option value="empleado" {{ $usuario->role === 'empleado' ? 'selected' : '' }}>Empleado</option>
                                <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                    @endif

                    <button class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection