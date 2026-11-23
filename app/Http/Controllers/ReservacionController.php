<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Reservacion;
use Illuminate\Support\Facades\DB;

class ReservacionController extends Controller
{
    public function index()
    {
        // El admin ve todas; el estudiante solo las suyas
        if (auth()->user()->role === 'admin') {
            $reservaciones = Reservacion::with(['user', 'equipo'])->orderByDesc('created_at')->get();
        } else {
            $reservaciones = Reservacion::with('equipo')
                ->where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->get();
        }

        return view('reservaciones.index', compact('reservaciones'));
    }

    public function mostrar(Reservacion $reservacion)
    {
        $this->revisarDueno($reservacion);

        return view('reservaciones.mostrar', compact('reservacion'));
    }

    public function reservar(Equipo $equipo)
    {
        // la transaccion y el bloqueo evitan que dos usuarios reserven el mismo equipo
        $reservado = DB::transaction(function () use ($equipo) {

            // lockForUpdate bloquea la fila hasta que termine la transaccion
            $equipoBloqueado = Equipo::where('id', $equipo->id)->lockForUpdate()->first();

            if ($equipoBloqueado->estado !== 'disponible') {
                return false;
            }

            Reservacion::create([
                'user_id' => auth()->id(),
                'equipo_id' => $equipoBloqueado->id,
                'estado' => 'activa',
            ]);

            $equipoBloqueado->update(['estado' => 'reservado']);

            return true;
        });

        if (!$reservado) {
            return redirect()->route('equipos.index')
                ->withErrors(['equipo' => 'Ese equipo ya no está disponible.']);
        }

        return redirect()->route('reservaciones.index')->with('exito', 'Equipo reservado.');
    }

    public function cancelar(Reservacion $reservacion)
    {
        $this->revisarDueno($reservacion);

        if ($reservacion->estado !== 'activa') {
            return back()->withErrors(['error' => 'Esa reservación ya estaba cancelada.']);
        }

        DB::transaction(function () use ($reservacion) {
            $reservacion->update(['estado' => 'cancelada']);
            $reservacion->equipo->update(['estado' => 'disponible']);
        });

        return redirect()->route('reservaciones.index')->with('exito', 'Reservación cancelada.');
    }

    // un estudiante solo puede tocar y ver sus propias reservaciones
    private function revisarDueno(Reservacion $reservacion)
    {
        if (auth()->user()->role !== 'admin' && $reservacion->user_id !== auth()->id()) {
            abort(403, 'Esa reservacion no es tuya.');
        }
    }
}