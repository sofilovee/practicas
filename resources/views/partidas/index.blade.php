@extends('Nav.navbar')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Juego del gato</h4>
    <div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('jugadores.index') }}" class="btn btn-info">Usuarios</a>
        @endif

        @if (auth()->user()->role === 'jugador')
            <form action="{{ route('partidas.crear') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-primary">Nueva partida</button>
            </form>
        @endif
    </div>
</div>

{{-- Vista del administrador: todas las partidas --}}
@if (auth()->user()->role === 'admin')
    <h5>Todas las partidas</h5>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>#</th>
                <th>Jugador X</th>
                <th>Jugador O</th>
                <th>Estado</th>
                <th>Resultado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($todas as $partida)
                <tr>
                    <td>{{ $partida->id }}</td>
                    <td>{{ $partida->jugadorX->name }}</td>
                    <td>{{ $partida->jugadorO->name ?? 'Esperando rival' }}</td>
                    <td>{{ $partida->estado }}</td>
                    <td>
                        @if ($partida->resultado === 'empate')
                            Empate
                        @elseif ($partida->resultado === 'cancelada')
                            Cancelada
                        @elseif ($partida->ganador)
                            Ganó {{ $partida->ganador->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('partidas.mostrar', $partida) }}" class="btn btn-info btn-sm">Ver</a>

                        @if (in_array($partida->estado, ['esperando', 'en_curso']))
                            <form action="{{ route('partidas.cancelar', $partida) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Cancelar esta partida?')">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-danger btn-sm">Cancelar partida</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay partidas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@else
    {{-- Vista del jugador --}}
    <h5>Partidas disponibles</h5>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>#</th>
                <th>Creada por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($disponibles as $partida)
                <tr>
                    <td>{{ $partida->id }}</td>
                    <td>{{ $partida->jugadorX->name }}</td>
                    <td>
                        <form action="{{ route('partidas.unirse', $partida) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm">Unirme</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay partidas esperando rival.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h5 class="mt-4">Mis partidas</h5>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>#</th>
                <th>Jugador X</th>
                <th>Jugador O</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($misPartidas as $partida)
                <tr>
                    <td>{{ $partida->id }}</td>
                    <td>{{ $partida->jugadorX->name }}</td>
                    <td>{{ $partida->jugadorO->name ?? 'Esperando rival' }}</td>
                    <td>{{ $partida->estado }}</td>
                    <td>
                        <a href="{{ route('partidas.mostrar', $partida) }}" class="btn btn-primary btn-sm">Abrir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No tienes partidas en curso.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h5 class="mt-4">Mi historial</h5>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>#</th>
                <th>Jugador X</th>
                <th>Jugador O</th>
                <th>Resultado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($historial as $partida)
                <tr>
                    <td>{{ $partida->id }}</td>
                    <td>{{ $partida->jugadorX->name }}</td>
                    <td>{{ $partida->jugadorO->name ?? '-' }}</td>
                    <td>
                        @if ($partida->resultado === 'empate')
                            Empate
                        @elseif ($partida->resultado === 'cancelada')
                            Cancelada
                        @elseif ($partida->resultado === 'abandono')
                            Ganó {{ $partida->ganador->name }} (por abandono)
                        @else
                            Ganó {{ $partida->ganador->name }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('partidas.mostrar', $partida) }}" class="btn btn-info btn-sm">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Todavía no hay partidas terminadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h5 class="mt-4">Jugadores registrados</h5>
    <ul class="list-group bg-white">
        @forelse ($jugadores as $jugador)
            <li class="list-group-item">{{ $jugador->name }}</li>
        @empty
            <li class="list-group-item">No hay más jugadores registrados.</li>
        @endforelse
    </ul>
@endif
@endsection