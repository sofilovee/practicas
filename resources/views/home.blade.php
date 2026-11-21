@extends('Nav.navbar')

@section('contenido')
<div class="card shadow-sm">
    <div class="card-body">
        <h4>Bienvenido, {{ auth()->user()->name }}</h4>
        <p>Rol: <strong>{{ auth()->user()->role }}</strong></p>
    </div>
</div>
@endsection