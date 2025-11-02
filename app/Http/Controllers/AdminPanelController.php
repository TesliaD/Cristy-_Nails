<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clientes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'usuario' => $request->nombre,
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
        $usuario = $cliente->usuario;

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'rol' => 'required|in:cliente,admin,empleado',
        ]);

        // Actualiza usuario
        $usuario->update([
            'usuario' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol,
        ]);

        // Actualiza cliente
        $cliente->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        return redirect()->back()->with('success', 'Cliente actualizado correctamente.');
    }

    // 🗑️ Eliminar cliente
    public function clientes_destroy($id)
    {
        $cliente = Clientes::findOrFail($id);
        $cliente->delete(); // Gracias a onDelete('cascade') también borra el usuario
        return redirect()->back()->with('success', 'Cliente eliminado correctamente.');
    }
}
