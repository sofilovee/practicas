@extends('Nav.navbar')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Verificacion</h4>
                <p>Te enviamos un codigo de 6 dígitos a tu correo. Vence en 5 minutos.</p>

                <form method="POST" action="{{ request()->fullUrl() }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" maxlength="6">
                    </div>
                    <button class="btn btn-primary w-100">Verificar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection