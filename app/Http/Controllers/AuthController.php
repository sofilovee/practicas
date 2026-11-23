<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('login');
    }

    public function iniciarSesion(Request $request)
    {
        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = User::where('email', $datos['email'])->first();


        if (!$usuario || !Hash::check($datos['password'], $usuario->password)) {
            return back()->withErrors([
                'email' => 'Correo o contraseña incorrectos.',
            ])->onlyInput('email');
        }

        if ($usuario->role !== 'admin') {
            Auth::login($usuario);
            $request->session()->regenerate();
            return redirect()->route('home');
        }
        $codigo = (string) random_int(100000, 999999);

        $usuario->update([
            'verification_code' => Hash::make($codigo),
            'verification_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($usuario->email)->send(new VerificationCodeMail($codigo));


        $request->session()->put('usuario_pendiente', $usuario->id);

        $url = URL::temporarySignedRoute('verificacion.mostrar', now()->addMinutes(5));

        return redirect($url);
    }

    public function mostrarVerificacion(Request $request)
    {
        if (!$request->session()->has('usuario_pendiente')) {
            return redirect()->route('login');
        }

        return view('verificacion');
    }

    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|digits:6',
        ]);

        $usuario = User::find($request->session()->get('usuario_pendiente'));

        if (!$usuario) {
            return redirect()->route('login');
        }

        if (!$usuario->verification_expires_at || now()->greaterThan($usuario->verification_expires_at)) {
            $request->session()->forget('usuario_pendiente');
            return redirect()->route('login')->withErrors([
                'email' => 'El código venció. Inicia sesión de nuevo.',
            ]);
        }

        // aqui revisamos si el codigo es correcto
        if (!Hash::check($request->codigo, $usuario->verification_code)) {
            return back()->withErrors(['codigo' => 'Código incorrecto.']);
        }

        // si es correcto inicia la sesion
        $usuario->update([
            'verification_code' => null,
            'verification_expires_at' => null,
        ]);

        $request->session()->forget('usuario_pendiente');
        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function mostrarRegistro()
{
    return view('registro');
}

public function registrar(Request $request)
{
    $datos = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    // el jugador s registra aqui
    $usuario = User::create([
        'name' => $datos['name'],
        'email' => $datos['email'],
        'password' => Hash::make($datos['password']),
        'role' => 'jugador',
    ]);

    Auth::login($usuario);
    $request->session()->regenerate();

    return redirect()->route('home');
}
}