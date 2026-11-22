@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Editar producto</h4>

                <form method="POST" action="{{ route('productos.update', $producto) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}">
                        <x-error-campo campo="nombre" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        <x-error-campo campo="descripcion" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <x-error-campo campo="categoria_id" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio</label>
                        <input type="text" name="precio" class="form-control" value="{{ old('precio', $producto->precio) }}">
                        <x-error-campo campo="precio" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Existencias</label>
                        <input type="text" name="existencias" class="form-control" value="{{ old('existencias', $producto->existencias) }}">
                        <x-error-campo campo="existencias" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="disponible" {{ old('estado', $producto->estado) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="agotado" {{ old('estado', $producto->estado) === 'agotado' ? 'selected' : '' }}>Agotado</option>
                        </select>
                        <x-error-campo campo="estado" />
                    </div>

                    <button class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection