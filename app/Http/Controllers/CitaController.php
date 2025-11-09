<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicios;

class CitaController extends Controller
{
    // 🔹 Mostrar calendario con citas
    public function index()
    {
        // Cargar las citas con relaciones
        $citas = Cita::with(['cliente', 'empleado', 'servicio'])->get();

        // Preparar los datos para FullCalendar
        $citasData = $citas->map(function ($cita) {
            return [
                'id' => $cita->id,
                'title' => ($cita->servicio->Nom_Servicio ?? 'Sin servicio') . ' - ' . ($cita->cliente->nombre ?? 'Sin cliente'),
                'start' => $cita->fecha . 'T' . $cita->hora,
                'backgroundColor' => $cita->estado == 'cancelada' ? '#ccc' : '#9ef5b0',
            ];
        });

        $clientes = Cliente::all();
        $empleados = Empleado::all();
        $servicios = Servicios::all();

        return view('admin.paneladmin', compact('citas', 'clientes', 'empleados', 'servicios', 'citasData'));
    }

    // 🔹 Guardar nueva cita
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        Cita::create($request->all());

        return response()->json(['success' => true]);
    }

    // 🔹 Eliminar cita
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return response()->json(['success' => true]);
    }
}
