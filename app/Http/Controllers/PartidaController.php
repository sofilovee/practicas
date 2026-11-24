<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Partida;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartidaController extends Controller
{
    public function index()
    {
        $todas = null;
        $disponibles = null;
        $misPartidas = null;
        $historial = null;
        $jugadores = null;

        if (auth()->user()->role === 'admin') {
            // El admin ve todas las partidas
            $todas = Partida::with(['jugadorX', 'jugadorO', 'ganador'])->orderByDesc('id')->get();
        } else {
            $yo = auth()->id();

            // partidas que se crean por otros que esperan un rival
            $disponibles = Partida::with('jugadorX')
                ->where('estado', 'esperando')
                ->where('jugador_x_id', '!=', $yo)
                ->get();

            // las partidas q tengo yo activas
            $misPartidas = Partida::with(['jugadorX', 'jugadorO'])
                ->where(function ($q) use ($yo) {
                    $q->where('jugador_x_id', $yo)->orWhere('jugador_o_id', $yo);
                })
                ->whereIn('estado', ['esperando', 'en_curso'])
                ->orderByDesc('id')
                ->get();

            // el historial d las partidaas
            $historial = Partida::with(['jugadorX', 'jugadorO', 'ganador'])
                ->where(function ($q) use ($yo) {
                    $q->where('jugador_x_id', $yo)->orWhere('jugador_o_id', $yo);
                })
                ->whereIn('estado', ['terminada', 'cancelada'])
                ->orderByDesc('id')
                ->get();

            // lista d jugadores registrados
            $jugadores = User::where('role', 'jugador')
                ->where('id', '!=', $yo)
                ->orderBy('name')
                ->get();
        }

        return view('partidas.index', compact('todas', 'disponibles', 'misPartidas', 'historial', 'jugadores'));
    }

    public function crear()
    {
        $partida = Partida::create([
            'jugador_x_id' => auth()->id(),
            'turno' => 'X',
            'estado' => 'esperando',
        ]);

        return redirect()->route('partidas.mostrar', $partida);
    }

    public function unirse(Partida $partida)
    {
        // con este bloqueo, dos jugadores no pueden unirse a la vez a la misma partida
        $error = DB::transaction(function () use ($partida) {
            $p = Partida::where('id', $partida->id)->lockForUpdate()->first();

            if ($p->estado !== 'esperando') {
                return 'Esa partida ya no está disponible.';
            }
            if ($p->jugador_x_id == auth()->id()) {
                return 'No puedes unirte a tu propia partida.';
            }

            $p->update([
                'jugador_o_id' => auth()->id(),
                'estado' => 'en_curso',
            ]);

            return null;
        });

        if ($error) {
            return redirect()->route('partidas.index')->withErrors(['partida' => $error]);
        }

        return redirect()->route('partidas.mostrar', $partida);
    }

    public function mostrar(Partida $partida)
    {
        $this->revisarParticipante($partida);

        $partida->load(['jugadorX', 'jugadorO', 'ganador', 'movimientos']);

        return view('partidas.mostrar', compact('partida'));
    }

    // lo consulta el nav cada 2 seg pa saber si hubo cambios
    public function estado(Partida $partida)
    {
        $this->revisarParticipante($partida);

        return response()->json([
            'movimientos' => $partida->movimientos()->count(),
            'estado' => $partida->estado,
        ]);
    }

    public function mover(Request $request, Partida $partida)
    {
        $request->validate([
            'posicion' => 'required|integer|between:0,8',
        ]);

        $posicion = (int) $request->posicion;

        // la transaccion con bloqueo evita dos movimientos al mismo tiemopo  en la misma partida
        $error = DB::transaction(function () use ($partida, $posicion) {
            $p = Partida::where('id', $partida->id)->lockForUpdate()->first();
            $simbolo = $p->simboloDe(auth()->id());

            if ($simbolo === null) {
                return 'No eres jugador de esta partida.';
            }
            if ($p->estado !== 'en_curso') {
                return 'La partida no esta en curso.';
            }
            if ($p->turno !== $simbolo) {
                return 'No es tu turno.';
            }
            if ($p->movimientos()->where('posicion', $posicion)->exists()) {
                return 'Esa casilla ya esta ocupada.';
            }

            Movimiento::create([
                'partida_id' => $p->id,
                'user_id' => auth()->id(),
                'posicion' => $posicion,
                'simbolo' => $simbolo,
            ]);

            // revisamos si con este movimintp alguien gano o hubo empate
            $p->load('movimientos');
            $tablero = $p->tablero();
            $ganadorSimbolo = $this->buscarGanador($tablero);

            if ($ganadorSimbolo) {
                $p->update([
                    'estado' => 'terminada',
                    'resultado' => $ganadorSimbolo,
                    'ganador_id' => auth()->id(),
                ]);
            } elseif (!in_array(null, $tablero, true)) {
                $p->update([
                    'estado' => 'terminada',
                    'resultado' => 'empate',
                ]);
            } else {
                $p->update(['turno' => $simbolo === 'X' ? 'O' : 'X']);
            }

            return null;
        });

        if ($error) {
            return redirect()->route('partidas.mostrar', $partida)->withErrors(['movimiento' => $error]);
        }

        return redirect()->route('partidas.mostrar', $partida);
    }

    public function abandonar(Partida $partida)
    {
        $this->revisarParticipante($partida);

        DB::transaction(function () use ($partida) {
            $p = Partida::where('id', $partida->id)->lockForUpdate()->first();
            $simbolo = $p->simboloDe(auth()->id());

            if ($p->estado === 'esperando' && $simbolo === 'X') {
                // nadie se habia unido: la partida simplemente se cancela
                $p->update(['estado' => 'cancelada', 'resultado' => 'cancelada']);
            } elseif ($p->estado === 'en_curso' && $simbolo !== null) {
                // si abandonas una partida en curso, gana el otro jugador
                $ganadorId = $simbolo === 'X' ? $p->jugador_o_id : $p->jugador_x_id;
                $p->update([
                    'estado' => 'terminada',
                    'resultado' => 'abandono',
                    'ganador_id' => $ganadorId,
                ]);
            }
        });

        return redirect()->route('partidas.index')->with('exito', 'Saliste de la partida.');
    }

    // solo el admin
    public function cancelar(Partida $partida)
    {
        if (in_array($partida->estado, ['esperando', 'en_curso'])) {
            $partida->update(['estado' => 'cancelada', 'resultado' => 'cancelada']);
        }

        return redirect()->route('partidas.index')->with('exito', 'Partida cancelada.');
    }

    // solo los dos jugadores de la partida y el admin pueden verla
    private function revisarParticipante(Partida $partida)
    {
        $esAdmin = auth()->user()->role === 'admin';

        if (!$esAdmin && $partida->simboloDe(auth()->id()) === null) {
            abort(403, 'No participas en esa partida.');
        }
    }

    // revisa las 8 lineas posibles, devuelve 'X', 'O' o null
    private function buscarGanador($tablero)
    {
        $lineas = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6],
        ];

        foreach ($lineas as $linea) {
            $a = $tablero[$linea[0]];
            if ($a && $a === $tablero[$linea[1]] && $a === $tablero[$linea[2]]) {
                return $a;
            }
        }

        return null;
    }
}