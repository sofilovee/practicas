@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Iniciar sesion</h4>

                <form method="POST" action="{{ route('login.enviar') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
                <p class="mt-3 mb-0">¿Quieres jugar? <a href="{{ route('registro') }}">Regístrate como jugador</a></p>
            </div>
        </div>
    </div>
</div>
@endsection