<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Clientes;

class ClientePanelController extends Controller
{
    // Mostrar el panel del cliente
    public function index()
    {
        $user = Auth::user();

        // Buscar el registro del cliente vinculado al usuario logueado
        $cliente = Clientes::where('usuario_id', $user->id)->first();

        return view('clientes.panelclientes', compact('user', 'cliente'));
    }

    // Actualizar perfil del cliente
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Actualizar usuario
        if ($request->filled('nombre')) {
            $user->nombre = $request->nombre;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Actualizar o crear el registro del cliente
        $cliente = Clientes::updateOrCreate(
            ['usuario_id' => $user->id],
            [
                'nombre' => $request->nombre ?? $user->nombre,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'fecha_nacimiento' => $request->fecha_nacimiento,
            ]
        );

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }
}
