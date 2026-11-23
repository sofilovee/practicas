<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JugadorController extends Controller
{
    public function index()
    {
        //eladminn  consulta todos los usuarios del sistema
        $usuarios = User::orderBy('role')->orderBy('name')->get();

        return view('jugadores.index', compact('usuarios'));
    }

    public function crear()
    {
        return view('jugadores.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'role' => 'jugador',
        ]);

        return redirect()->route('jugadores.index')->with('exito', 'Jugador registrado.');
    }

    public function editar(User $jugador)
    {
        $this->soloJugador($jugador);

        return view('jugadores.editar', compact('jugador'));
    }

    public function update(Request $request, User $jugador)
    {
        $this->soloJugador($jugador);

        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $jugador->id,
            'password' => 'nullable|min:8',
        ]);

        $jugador->name = $datos['name'];
        $jugador->email = $datos['email'];

        // Si no escribe contraseña, se queda la que ya tenia
        if (!empty($datos['password'])) {
            $jugador->password = Hash::make($datos['password']);
        }

        $jugador->save();

        return redirect()->route('jugadores.index')->with('exito', 'Jugador actualizado.');
    }

    public function destroy(User $jugador)
    {
        $this->soloJugador($jugador);

        // \se borran partidas y movimientos
        Partida::where('jugador_x_id', $jugador->id)
            ->orWhere('jugador_o_id', $jugador->id)
            ->delete();

        $jugador->forceDelete();

        return redirect()->route('jugadores.index')->with('exito', 'Jugador eliminado junto con sus partidas.');
    }

    // edita y elimina usuarios con rol d juagdor
    private function soloJugador(User $usuario)
    {
        if ($usuario->role !== 'jugador') {
            abort(404);
        }
    }
}