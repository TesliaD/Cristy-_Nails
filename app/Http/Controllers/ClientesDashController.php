<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // o App\Models\Cliente si usas tabla clientes

class ClientesDashController extends Controller
{
    // Mostrar el panel del cliente
    public function index()
    {
        $user = Auth::user(); // usuario autenticado
        return view('panelclientes', compact('user'));
    }

    // Actualizar perfil desde el panel
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email'  => 'required|email|unique:usuarios,email,'.$user->id, // ajusta si tu PK no es id
            'telefono' => 'nullable|string|max:30',
            'direccion'=> 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->nombre = $data['nombre'];
        $user->email  = $data['email'];
        $user->telefono = $data['telefono'] ?? $user->telefono;
        $user->direccion = $data['direccion'] ?? $user->direccion;

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}