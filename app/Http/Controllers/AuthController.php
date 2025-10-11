<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // 👈 Importante: asegúrate de tener esta línea

class AuthController extends Controller
{
    // 🔹 Mostrar formulario de login
    public function showLogin()
    {
        return view('login'); 
    }

    // 🔹 Iniciar sesión
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            switch (Auth::user()->rol) {
                case 'admin':
                    return redirect()->route('admin.panel');
                case 'cliente':
                    return redirect()->route('agendar');
                case 'empleado':
                    return redirect()->route('empleado.panel');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => '⚠️ Rol no reconocido']);
            }
        }

        return back()->withErrors([
            'email' => '⚠️ Credenciales incorrectas',
        ]);
    }

    // 🔹 Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // 🔹 Mostrar formulario de registro
    public function mostrarRegistro()
    {
        return view('registro');
    }

    // 🔹 Registrar nuevos usuarios
    public function registrarUsuario(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Crear usuario con rol por defecto
        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'cliente', // 👈 le damos rol por defecto
        ]);

        // Inicia sesión automáticamente después de registrarse (opcional)
        Auth::login($user);

        // Redirige según su rol (cliente por defecto)
        return redirect()->route('agendar')
            ->with('success', '¡Bienvenido! Tu cuenta se creó correctamente.');
    }
}
