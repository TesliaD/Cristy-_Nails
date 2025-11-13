<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clientes;
use App\Models\Cita;
use App\Models\Servicios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmpleadoController;
use Carbon\Carbon;

class AdminPanelController extends Controller
{
    // 🧩 Mostrar vista de clientes
    public function clientes_index()
    {
        $clientes = Clientes::with('usuario')->get(); // Carga clientes + usuario relacionado
        return view('admin.paneladmin', compact('clientes'));
    }




    // 💾 Guardar nuevo cliente
    public function clientes_store(Request $request)
    {
        // ✅ Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'rol' => 'required|in:cliente,admin,empleado',
            'password' => 'required|min:6',
        ]);

        // ✅ Crear usuario (tabla usuarios)
        $usuario = User::create([
            'usuario' => $request->usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        // ✅ Crear cliente (tabla clientes)
        Clientes::create([
            'usuario_id' => $usuario->id,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        return redirect()->back()->with('success', 'Cliente agregado correctamente.');
    }

    // 🔄 Actualizar cliente
    public function clientes_update(Request $request, $id)
    {
        $cliente = Clientes::findOrFail($id);
        $user = $cliente->usuario;

        // Actualizar datos
        $user->usuario = $request->usuario;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->save();

        $cliente->nombre = $request->nombre;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->save();

        // Contraseña opcional
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return redirect()->back()->with('success', 'Cliente actualizado correctamente.');
    }

    // 🗑️ Eliminar cliente
    public function clientes_destroy($id)
    {
        $cliente = Clientes::findOrFail($id);

        // Eliminar también el usuario asociado
        if ($cliente->usuario_id) {
            $cliente->usuario()->delete(); // elimina el usuario y por cascada el cliente
        } else {
            $cliente->delete();
        }


        return redirect()->back()->with('success', 'Cliente y usuario eliminados correctamente.');
    }

}
