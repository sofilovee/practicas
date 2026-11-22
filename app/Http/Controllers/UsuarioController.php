<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
{
    $usuarios = User::whereIn('role', ['admin', 'empleado'])->get();

    // Solo el admin ve a los usuarios deshabilitados
    $eliminados = null;
    if (auth()->user()->role === 'admin') {
        $eliminados = User::onlyTrashed()->whereIn('role', ['admin', 'empleado'])->get();
    }

    return view('usuarios.index', compact('usuarios', 'eliminados'));
}

    public function crear()
    {
        return view('usuarios.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'role' => 'nullable|in:admin,empleado',
        ]);

        // el empleado siempre registra empleados, perooo solo el admin elige el rol
        $rol = 'empleado';
        if (auth()->user()->role === 'admin' && !empty($datos['role'])) {
            $rol = $datos['role'];
        }

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'role' => $rol,
        ]);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario registrado.');
    }

    public function mostrar(User $usuario)
    {
         $this->soloDelSistema($usuario);
        return view('usuarios.mostrar', compact('usuario'));
    }

    public function editar(User $usuario)
    {
         $this->soloDelSistema($usuario);
        // un empleado no puede editar a un administrador
        if (auth()->user()->role !== 'admin' && $usuario->role === 'admin') {
            abort(403, 'No puedes editar a un administrador.');
        }

        return view('usuarios.editar', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
         $this->soloDelSistema($usuario);
        if (auth()->user()->role !== 'admin' && $usuario->role === 'admin') {
            abort(403, 'No puedes editar a un administrador.');
        }

        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|min:8',
            'role' => 'nullable|in:admin,empleado',
        ]);

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];

        // Si no escribe contraseña, se queda la que ya tenia
        if (!empty($datos['password'])) {
            $usuario->password = Hash::make($datos['password']);
        }

        // solo el admin puede cambiar el rol
        if (auth()->user()->role === 'admin' && !empty($datos['role'])) {
            $usuario->role = $datos['role'];
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('exito', 'Usuario actualizado.');
    }

    // aqui nada mas lo deshabilitamos a menos q seas tu miesmo
    public function destroy(User $usuario)
    {
         $this->soloDelSistema($usuario);
        if ($usuario->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propio usuario.']);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('exito', 'Usuario deshabilitado.');
    }

    // esto elimina permianente
    public function forceDelete($id)
    {
        $usuario = User::withTrashed()->findOrFail($id);

        if ($usuario->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propio usuario.']);
        }

        $usuario->forceDelete();

        return redirect()->route('usuarios.index')->with('exito', 'Usuario eliminado permanentemente.');
    }

    public function restaurar($id)
    {
        $usuario = User::onlyTrashed()->findOrFail($id);
        $usuario->restore();

        return redirect()->route('usuarios.index')->with('exito', 'Usuario restaurado.');
    }

    // esto solo trabaja con administradores y empleados
private function soloDelSistema(User $usuario)
{
    if (!in_array($usuario->role, ['admin', 'empleado'])) {
        abort(404);
    }
}
}