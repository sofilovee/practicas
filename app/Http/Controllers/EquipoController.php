<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index()
    {
        // el admin ve todo, el estudiante solo los disponibles
        if (auth()->user()->role === 'admin') {
            $equipos = Equipo::orderBy('nombre')->get();
        } else {
            $equipos = Equipo::where('estado', 'disponible')->orderBy('nombre')->get();
        }

        return view('equipos.index', compact('equipos'));
    }

    public function crear()
    {
        return view('equipos.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255|unique:equipos,nombre',
            'descripcion' => 'required|string',
        ]);

        Equipo::create($datos);

        return redirect()->route('equipos.index')->with('exito', 'Equipo registrado.');
    }

    public function editar(Equipo $equipo)
    {
        return view('equipos.editar', compact('equipo'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255|unique:equipos,nombre,' . $equipo->id,
            'descripcion' => 'required|string',
        ]);

        $equipo->update($datos);

        return redirect()->route('equipos.index')->with('exito', 'Equipo actualizado.');
    }

    public function destroy(Equipo $equipo)
    {
        if ($equipo->reservaciones()->where('estado', 'activa')->exists()) {
            return back()->withErrors(['error' => 'No puedes eliminar un equipo con una reservación activa.']);
        }

        $equipo->delete();

        return redirect()->route('equipos.index')->with('exito', 'Equipo eliminado.');
    }
}