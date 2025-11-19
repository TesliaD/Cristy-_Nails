<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Clientes;
use App\Models\Cita;
use App\Models\Servicios;

class ClientePanelController extends Controller
{
    // Mostrar el panel del cliente
    public function index()
    {
        $user = Auth::user();
        $cliente = Clientes::where('usuario_id', $user->id)->first();

        // 🔹 Citas del cliente
        $citas = Cita::where('cliente_id', $cliente->id)
                    ->orderBy('fecha', 'asc')
                    ->orderBy('hora', 'asc')
                    ->get();

        // 🔹 Servicios disponibles
        $servicios = Servicios::all();

        return view('clientes.panelclientes', compact('user', 'cliente', 'citas', 'servicios'));
    }

    // --------------------------------------------------------------------
    // 🔹 NUEVO: Vista para agendar una cita
    // --------------------------------------------------------------------
    public function vistaAgendar()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para agendar una cita.');
        }

        // Cargar servicios activos
        $servicios = Servicios::where('Activo', true)->get();

        return view('clientes.agendar', compact('servicios'));
    }

    // --------------------------------------------------------------------
    // 🔹 NUEVO: Guardar cita desde vista "agendar"
    // --------------------------------------------------------------------
    public function agendar(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Debes iniciar sesión para agendar.');
        }

        $request->validate([
            'servicio' => 'required|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'notas' => 'nullable|string'
        ]);

        $user = Auth::user();
        $cliente = Clientes::where('usuario_id', $user->id)->first();

        if (!$cliente) {
            return back()->with('error', 'No se encontró tu perfil de cliente.');
        }

        // Registrar cita
        Cita::create([
            'cliente_id' => $cliente->id,
            'empleado_id' => null, // Se asignará después en admin
            'servicio_id' => $request->servicio,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'estado' => 'pendiente',
            'notas' => $request->notas
        ]);

        return back()->with('mensaje', '¡Tu cita ha sido agendada con éxito!');
    }

    // --------------------------------------------------------------------
    // Actualizar perfil del cliente
    // --------------------------------------------------------------------
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

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        Clientes::updateOrCreate(
            ['usuario_id' => $user->id],
            [
                'nombre'            => $request->nombre,
                'telefono'          => $request->telefono,
                'direccion'         => $request->direccion,
                'fecha_nacimiento'  => $request->fecha_nacimiento,
            ]
        );

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    // --------------------------------------------------------------------
    // Guardar cita desde panel del cliente
    // --------------------------------------------------------------------
    public function storeCita(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'servicio_id' => 'required',
        ]);

        $cliente = Auth::user()->cliente;

        Cita::create([
            'fecha'         => $request->fecha,
            'hora'          => $request->hora,
            'servicio_id'   => $request->servicio_id,
            'cliente_id'    => $cliente->id,
            'empleado_id'   => null,
            'notas'         => $request->notas,
        ]);

        return redirect()->back()->with('success', 'Cita creada correctamente.');
    }

    // --------------------------------------------------------------------
    // Cancelar cita
    // --------------------------------------------------------------------
    public function borrarCita($id)
    {
        $cliente = Auth::user()->cliente;

        $cita = Cita::where('id', $id)
                    ->where('cliente_id', $cliente->id)
                    ->firstOrFail();

        $cita->delete();

        return redirect()->back()->with('success', 'Cita cancelada.');
    }
}
