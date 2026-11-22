<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        // el alumno puede ver nada mas sus datos
        if (auth()->user()->role === 'alumno') {
            $alumno = auth()->user()->alumno;
            return view('alumnos.mio', compact('alumno'));
        }

        // Criterios de orden permitidos (así nadie manda columnas raras por la URL)
        $columnas = [
            'nombre' => 'users.name',
            'calificacion' => 'alumnos.calificacion',
            'faltas' => 'alumnos.faltas',
        ];

        $orden = $request->get('orden', 'nombre');
        $direccion = $request->get('direccion', 'asc');

        if (!isset($columnas[$orden])) {
            $orden = 'nombre';
        }
        if (!in_array($direccion, ['asc', 'desc'])) {
            $direccion = 'asc';
        }

        $alumnos = Alumno::join('users', 'users.id', '=', 'alumnos.user_id')
            ->select('alumnos.*', 'users.name as nombre')
            ->orderBy($columnas[$orden], $direccion)
            ->get();

        return view('alumnos.index', compact('alumnos', 'orden', 'direccion'));
    }

    public function crear()
    {
        return view('alumnos.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'faltas' => 'required|integer|min:0|max:50',
            'calificacion' => 'required|numeric|min:0|max:10',
        ]);

        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'role' => 'alumno',
        ]);

        Alumno::create([
            'user_id' => $usuario->id,
            'faltas' => $datos['faltas'],
            'calificacion' => $datos['calificacion'],
        ]);

        return redirect()->route('alumnos.index')->with('exito', 'Alumno registrado.');
    }

    public function editar(Alumno $alumno)
    {
        return view('alumnos.editar', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|unique:users,email,' . $alumno->user_id,
            'faltas' => 'required|integer|min:0|max:50',
            'calificacion' => 'required|numeric|min:0|max:10',
        ]);

        $alumno->user->update([
            'name' => $datos['name'],
            'email' => $datos['email'],
        ]);

        $alumno->update([
            'faltas' => $datos['faltas'],
            'calificacion' => $datos['calificacion'],
        ]);

        return redirect()->route('alumnos.index')->with('exito', 'Alumno actualizado.');
    }

    public function destroy(Alumno $alumno)
    {
        
        $alumno->user->forceDelete();

        return redirect()->route('alumnos.index')->with('exito', 'Alumno eliminado.');
    }
}