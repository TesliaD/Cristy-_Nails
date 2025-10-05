<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login'); 
    }

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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

