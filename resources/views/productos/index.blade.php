@extends('Nav.navbar')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Productos</h4>
    <div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('productos.crear') }}" class="btn btn-primary">Nuevo producto</a>
        @endif
    </div>
</div>

<form method="GET" action="{{ route('productos.index') }}" class="card card-body mb-3">
    <div class="row g-2">
        <div class="col-md-3">
            <label class="form-label">Buscar por nombre</label>
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Categoría</label>
            <select name="categoria" class="form-select">
                <option value="">Todas</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Precio mínimo</label>
            <input type="number" step="0.01" name="precio_min" class="form-control" value="{{ request('precio_min') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Precio máximo</label>
            <input type="number" step="0.01" name="precio_max" class="form-control" value="{{ request('precio_max') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="disponible" {{ request('estado') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="agotado" {{ request('estado') === 'agotado' ? 'selected' : '' }}>Agotado</option>
            </select>
        </div>
    </div>

    <div class="row g-2 mt-1">
        <div class="col-md-4">
            <label class="form-label">Ordenar por</label>
            <select name="orden" class="form-select">
                <option value="fecha_desc" {{ $orden === 'fecha_desc' ? 'selected' : '' }}>Fecha de registro (más recientes)</option>
                <option value="fecha_asc" {{ $orden === 'fecha_asc' ? 'selected' : '' }}>Fecha de registro (más antiguos)</option>
                <option value="nombre_asc" {{ $orden === 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                <option value="nombre_desc" {{ $orden === 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                <option value="precio_desc" {{ $orden === 'precio_desc' ? 'selected' : '' }}>Precio (mayor a menor)</option>
                <option value="precio_asc" {{ $orden === 'precio_asc' ? 'selected' : '' }}>Precio (menor a mayor)</option>
                <option value="existencias_desc" {{ $orden === 'existencias_desc' ? 'selected' : '' }}>Existencias (mayor a menor)</option>
                <option value="existencias_asc" {{ $orden === 'existencias_asc' ? 'selected' : '' }}>Existencias (menor a mayor)</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Por página</label>
            <select name="por_pagina" class="form-select">
                @foreach ([10, 15, 25, 50] as $cantidad)
                    <option value="{{ $cantidad }}" {{ $porPagina == $cantidad ? 'selected' : '' }}>{{ $cantidad }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-dark me-2">Aplicar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Limpiar filtros</a>
        </div>
    </div>
</form>

<p>
    Total de productos encontrados: <strong>{{ $productos->total() }}</strong>.
    Página <strong>{{ $productos->currentPage() }}</strong> de <strong>{{ $productos->lastPage() }}</strong>.
</p>

<div class="table-responsive">
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Existencias</th>
                <th>Estado</th>
                <th>Fecha de registro</th>
                @if (auth()->user()->role === 'admin')
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->existencias }}</td>
                    <td><x-etiqueta-estado :estado="$producto->estado" /></td>
                    <td>{{ $producto->created_at->format('d/m/Y') }}</td>

                    @if (auth()->user()->role === 'admin')
                        <td>
                            <a href="{{ route('productos.editar', $producto) }}" class="btn btn-warning btn-sm">Editar</a>

                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No se encontraron productos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $productos->links() }}
@endsection