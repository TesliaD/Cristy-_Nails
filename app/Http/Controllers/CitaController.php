<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Clientes;
use App\Models\User;
use App\Models\Servicios;

class CitaController extends Controller
{
    // Mostrar calendario con citas
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
                'backgroundColor' => $cita->estado == 'cancelada' ? '#ff0000' : '#9ef5b0',
            ];
        });

        // Cargar clientes, empleados y servicios
        $clientes = Clientes::all();
        $empleados = User::where('rol', 'empleado')->get();
        $servicios = Servicios::all();

        // Enviar datos a la vista del panel
        return view('admin.paneladmin', compact('citas', 'clientes', 'empleados', 'servicios', 'citasData'));
    }

    // Guardar nueva cita
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

    // Actualizar cita existente
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

    // Eliminar cita
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return response()->json(['success' => true]);
    }

    //Store para los clientes
    public function storeCliente(Request $request)
    {
        $request->validate([
            'servicio' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'notas' => 'nullable|string',
        ]);

        // 🔍 Obtener al cliente que corresponde al usuario loggeado
        $cliente = Clientes::where('usuario_id', auth()->user()->id)->first();

        if (!$cliente) {
            return redirect()->back()->with('error', 'No se encontró el cliente asociado al usuario.');
        }

        // Crear la cita
        Cita::create([
            'cliente_id' => $cliente->id,
            'empleado_id' => null, // o si quieres asignar después
            'servicio_id' => $request->servicio,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'notas' => $request->notas,
            'estado' => 'pendiente'
        ]);

        return redirect()->route('dashboard')->with('mensaje', 'Cita agendada con éxito.');
    }

    //Cancelar cita del cliente
    public function cancelar($id)
    {
        $cita = Cita::findOrFail($id);

        // Validación: solo el dueño puede cancelar
        $cliente = Clientes::where('usuario_id', auth()->id())->first();
        if (!$cliente || $cita->cliente_id != $cliente->id) {
            return redirect()->back()->with('error', 'No tienes permiso para cancelar esta cita.');
        }

        $cita->estado = "cancelada";
        $cita->save();

        return redirect()->back()->with('success', 'La cita ha sido cancelada correctamente.');
    }


}
