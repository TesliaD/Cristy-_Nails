<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Clientes;
use App\Models\User;
use App\Models\Servicios;

class CitaController extends Controller
{
    // 🔹 Mostrar calendario con citas
    public function index()
    {
        // Cargar las citas con sus relaciones
        $citas = Cita::with(['cliente', 'empleado', 'servicio'])->get();

        // Preparar datos para FullCalendar
        $citasData = $citas->map(function ($cita) {
            return [
                'id' => $cita->id,
                'title' => ($cita->servicio->Nom_Servicio ?? 'Sin servicio') . ' - ' . ($cita->empleado->usuario ?? 'Sin empleado'),
                'start' => $cita->fecha . 'T' . $cita->hora,
                'hora' => $cita->hora,
                'fecha' => $cita->fecha,
                'cliente_id' => $cita->cliente_id,
                'servicio_id' => $cita->servicio_id,
                'empleado_id' => $cita->empleado_id,
                'notas' => $cita->notas,
                'backgroundColor' => $cita->estado == 'cancelada' ? '#ccc' : '#9ef5b0',
            ];
        });

        // Cargar clientes, empleados y servicios
        $clientes = Clientes::all();
        $empleados = User::where('rol', 'empleado')->get();
        $servicios = Servicios::all();

        // Enviar datos a la vista del panel
        return view('admin.paneladmin', compact('citas', 'clientes', 'empleados', 'servicios', 'citasData'));
    }

    // 🔹 Guardar nueva cita
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:usuarios,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'notas' => 'nullable|string',
        ]);

        Cita::create($request->all());

        return response()->json(['success' => true]);
    }

    // 🔹 Actualizar cita existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:usuarios,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'notas' => 'nullable|string',
        ]);

        $cita = Cita::findOrFail($id);
        $cita->update($request->all());

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
