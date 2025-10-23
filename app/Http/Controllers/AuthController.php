<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            $user = Auth::user();

            // Redirigir según el rol
            switch ($user->rol) {
                case 'admin':
                    return redirect()->route('admin.panel');
                case 'empleado':
                    return redirect()->route('empleado.panel');
                case 'cliente':
                    return redirect()->route('agendar');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => '⚠️ Rol no reconocido']);
            }
        }

        // Si las credenciales son incorrectas
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Crear usuario (tabla: usuarios)
        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'cliente',
        ]);

        // Crear registro en tabla clientes relacionado
        Clientes::create([
            'usuario_id' => $user->id,
            'nombre' => $request->input('cliente_nombre', $request->nombre),
            'telefono' => $request->input('telefono'),
            'direccion' => $request->input('direccion'),
        ]);

        // Iniciar sesión automáticamente después de registrarse
        Auth::login($user);

        return redirect()->route('agendar')
            ->with('success', '¡Bienvenido! Tu cuenta se creó correctamente.');
    }
}
