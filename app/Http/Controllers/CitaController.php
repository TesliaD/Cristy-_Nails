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
        $citas = Cita::with(['cliente', 'empleado', 'servicio'])->get();

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

        $clientes = Clientes::all();
        $empleados = User::where('rol', 'empleado')->get();
        $servicios = Servicios::all();

        return view('admin.paneladmin', compact('citas', 'clientes', 'empleados', 'servicios', 'citasData'));
    }

    // Guardar nueva cita desde administración
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

    // Actualizar cita desde administración
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

    // ============================================================
    // --------- STORE DE CLIENTE (CON VALIDACIÓN DE EMPALMES) ----
    // ============================================================

    public function storeCliente(Request $request)
    {
        $request->validate([
            'servicio' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'notas' => 'nullable|string',
        ]);

        // Cliente del usuario autenticado
        $cliente = Clientes::where('usuario_id', auth()->user()->id)->first();

        if (!$cliente) {
            return redirect()->back()->with('error', 'No se encontró el cliente asociado al usuario.');
        }

        // Servicio elegido
        $servicio = Servicios::findOrFail($request->servicio);
        $duracion = $servicio->Duracion; // minutos

        // Calcular hora fin
        $horaInicio = $request->hora;
        $horaFin = date("H:i:s", strtotime($horaInicio . " + $duracion minutes"));

        // Validación de empalme
        $empalme = Cita::where('fecha', $request->fecha)
            ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->whereBetween('hora', [$horaInicio, $horaFin]) // Cita dentro del rango
                      ->orWhereRaw("ADDTIME(citas.hora, SEC_TO_TIME(servicios.Duracion * 60)) > ?", [$horaInicio]);
            })
            ->exists();

        if ($empalme) {
            return redirect()->back()->withErrors([
                'hora' => "⛔ Ya existe una cita que se empalma con este horario."
            ])->withInput();
        }

        // Crear la cita
        Cita::create([
            'cliente_id' => $cliente->id,
            'empleado_id' => null, // o assignar después manualmente
            'servicio_id' => $request->servicio,
            'fecha' => $request->fecha,
            'hora' => $horaInicio,
            'notas' => $request->notas,
            'estado' => 'pendiente'
        ]);

        return redirect()->route('dashboard')->with('mensaje', '✨ Cita agendada con éxito.');
    }

    // Cancelar cita del cliente
    public function cancelar($id)
    {
        $cita = Cita::findOrFail($id);

        $cliente = Clientes::where('usuario_id', auth()->id())->first();

        if (!$cliente || $cita->cliente_id != $cliente->id) {
            return redirect()->back()->with('error', 'No tienes permiso para cancelar esta cita.');
        }

        $cita->estado = "cancelada";
        $cita->save();

        return redirect()->back()->with('success', 'La cita ha sido cancelada correctamente.');
    }

    
    // ============================================================
    // ---------- OBTENER HORAS DISPONIBLES POR FECHA ------------
    // ============================================================

    public function getHorasDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'servicio_id' => 'required|exists:servicios,id'
        ]);

        $fecha = $request->fecha;
        $servicio = Servicios::findOrFail($request->servicio_id);
        $duracion = $servicio->Duracion; // minutos

        // Horario del negocio
        $horaInicio = "09:00";
        $horaFin = "20:00";

        // Generar bloques de tiempo
        $intervalos = [];
        $horaActual = strtotime($horaInicio);

        while ($horaActual < strtotime($horaFin)) {
            $inicio = date('H:i', $horaActual);
            $fin = date('H:i', strtotime("+{$duracion} minutes", $horaActual));

            if (strtotime($fin) <= strtotime($horaFin)) {
                $intervalos[] = [
                    'inicio' => $inicio,
                    'fin' => $fin
                ];
            }

            $horaActual = strtotime("+30 minutes", $horaActual);
        }

        // Cargar citas ocupadas ese día
        $citas = Cita::where('fecha', $fecha)
            ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
            ->get(['hora', 'servicios.Duracion']);

        // Filtrar horarios ocupados
        $disponibles = [];

        foreach ($intervalos as $bloque) {
            $estaOcupado = false;

            foreach ($citas as $cita) {
                $inicioCita = $cita->hora;
                $finCita = date("H:i", strtotime($inicioCita . " + {$cita->Duracion} minutes"));

                // Si hay empalme, bloquear horario
                if (
                    ($bloque['inicio'] >= $inicioCita && $bloque['inicio'] < $finCita) ||
                    ($bloque['fin'] > $inicioCita && $bloque['fin'] <= $finCita)
                ) {
                    $estaOcupado = true;
                    break;
                }
            }

            if (!$estaOcupado) {
                $disponibles[] = $bloque['inicio'];
            }
        }

        return response()->json($disponibles);
    }


}
