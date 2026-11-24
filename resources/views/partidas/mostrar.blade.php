@extends('Nav.navbar')

@section('contenido')

@php
    $tablero = $partida->tablero();
    $miSimbolo = $partida->simboloDe(auth()->id());
    $miTurno = $partida->estado === 'en_curso' && $partida->turno === $miSimbolo;
@endphp

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Partida #{{ $partida->id }}</h4>

                <p>
                    <strong>X:</strong> {{ $partida->jugadorX->name }}<br>
                    <strong>O:</strong> {{ $partida->jugadorO->name ?? 'Esperando rival...' }}
                </p>

                @if ($miSimbolo)
                    <p>Tú juegas con: <strong>{{ $miSimbolo }}</strong></p>
                @endif

                {{-- Mensaje segun el estado de la partida --}}
                @if ($partida->estado === 'esperando')
                    <div class="alert alert-info">Esperando a que otro jugador se una.</div>
                @elseif ($partida->estado === 'en_curso')
                    @if ($miTurno)
                        <div class="alert alert-success">Es tu turno.</div>
                    @else
                        <div class="alert alert-warning">Turno de {{ $partida->turno }}.</div>
                    @endif
                @elseif ($partida->resultado === 'empate')
                    <div class="alert alert-secondary">La partida terminó en empate.</div>
                @elseif ($partida->resultado === 'cancelada')
                    <div class="alert alert-secondary">La partida fue cancelada.</div>
                @elseif ($partida->resultado === 'abandono')
                    <div class="alert alert-primary">Ganó {{ $partida->ganador->name }} porque su rival abandonó.</div>
                @else
                    <div class="alert alert-primary">¡Ganó {{ $partida->ganador->name }}!</div>
                @endif

                {{-- Tablero de 3 x 3 --}}
                <div style="display: grid; grid-template-columns: repeat(3, 90px); gap: 6px; justify-content: center;" class="mb-3">
                    @foreach ($tablero as $i => $casilla)
                        @if ($casilla)
                            <div class="border rounded d-flex align-items-center justify-content-center bg-white fs-1 fw-bold"
                                 style="width: 90px; height: 90px;">{{ $casilla }}</div>
                        @elseif ($miTurno)
                            <form method="POST" action="{{ route('partidas.mover', $partida) }}">
                                @csrf
                                <input type="hidden" name="posicion" value="{{ $i }}">
                                <button class="btn btn-outline-primary" style="width: 90px; height: 90px;"></button>
                            </form>
                        @else
                            <div class="border rounded bg-light" style="width: 90px; height: 90px;"></div>
                        @endif
                    @endforeach
                </div>

                <a href="{{ route('partidas.index') }}" class="btn btn-secondary">Volver a partidas</a>

                @if ($miSimbolo && in_array($partida->estado, ['esperando', 'en_curso']))
                    <form action="{{ route('partidas.abandonar', $partida) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('¿Abandonar la partida?')">
                        @csrf
                        <button class="btn btn-danger">Abandonar</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Actualizacion del tablero con consultas periodicas (polling) --}}
@if (in_array($partida->estado, ['esperando', 'en_curso']))
<script>
    let movimientos = {{ $partida->movimientos->count() }};
    let estado = '{{ $partida->estado }}';

    //cada 2 seg se pregunta al serv si algo cambio
    let sondeo = setInterval(function () {
        fetch('{{ route('partidas.estado', $partida) }}', { headers: { 'Accept': 'application/json' } })
            .then(function (respuesta) { return respuesta.json(); })
            .then(function (datos) {
                if (datos.movimientos !== movimientos || datos.estado !== estado) {
                    location.reload();
                }
            });
    }, 2000);
</script>
@endif
@endsection