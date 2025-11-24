<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Clientes;
use App\Models\User;
use App\Models\Servicios;
use Illuminate\Validation\ValidationException;

class CitaController extends Controller
{
    // Mostrar calendario con citas (admin/empleado)
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

    // --------------------------------
    // Helper solapamiento
    // --------------------------------
    protected function haySolapamiento($fecha, $horaInicio, $horaFin, $empleadoId = null, $ignoreCitaId = null)
    {
        $query = Cita::where('fecha', $fecha);

        if ($empleadoId !== null) {
            $query->where('empleado_id', $empleadoId);
        }

        if ($ignoreCitaId !== null) {
            $query->where('citas.id', '<>', $ignoreCitaId); // FIX ✔
        }

        $citas = $query->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
                       ->get(['citas.id', 'citas.hora as inicio_cita', 'servicios.Duracion']);

        foreach ($citas as $c) {
            $inicioExistente = date('H:i:s', strtotime($c->inicio_cita));
            $finExistente = date('H:i:s', strtotime($inicioExistente . " + {$c->Duracion} minutes"));

            if ( ($horaInicio < $finExistente) && ($horaFin > $inicioExistente) ) {
                return true;
            }
        }

        return false;
    }

    // --------------------------------
    // Guardar nueva cita
    // --------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'  => 'required|exists:clientes,id',
            'empleado_id' => 'nullable|exists:usuarios,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha'       => 'required|date',
            'hora'        => 'required',
            'notas'       => 'nullable|string',
        ]);

        $servicio = Servicios::findOrFail($request->servicio_id);
        if (!$servicio->Activo) {
            return response()->json(['success' => false, 'message' => 'El servicio no está activo.'], 422);
        }

        $horaInicio = date('H:i:s', strtotime($request->hora));
        $duracion = intval($servicio->Duracion) ?: 0;
        $horaFin = date('H:i:s', strtotime($horaInicio . " + {$duracion} minutes"));

        $horaInicioDia = "09:00:00";
        $horaFinDia = "20:00:00";

        if ($horaInicio < $horaInicioDia || $horaFin > $horaFinDia) {
            return response()->json(['success'=>false, 'message' => 'La cita debe estar dentro del horario de atención.'], 422);
        }

        $fechaHora = date('Y-m-d H:i:s', strtotime($request->fecha . ' ' . $horaInicio));
        if (strtotime($fechaHora) < time()) {
            return response()->json(['success'=>false, 'message' => 'No puedes agendar en el pasado.'], 422);
        }

        // Empalme cliente
        $empCliente = Cita::where('fecha', $request->fecha)
            ->where('cliente_id', $request->cliente_id)
            ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
            ->get(['citas.hora as inicio_cita','servicios.Duracion'])
            ->contains(function ($c) use ($horaInicio, $horaFin) {
                $inicioExistente = date('H:i:s', strtotime($c->inicio_cita));
                $finExistente = date('H:i:s', strtotime($inicioExistente . " + {$c->Duracion} minutes"));
                return ($horaInicio < $finExistente) && ($horaFin > $inicioExistente);
            });

        if ($empCliente) {
            return response()->json(['success' => false, 'message' => 'El cliente ya tiene una cita que se empalma.'], 422);
        }

        // Empalme empleado
        if ($request->empleado_id) {
            if ($this->haySolapamiento($request->fecha, $horaInicio, $horaFin, $request->empleado_id)) {
                return response()->json(['success' => false, 'message' => 'El empleado tiene otra cita en ese horario.'], 422);
            }
        }

        $cita = Cita::create([
            'cliente_id'  => $request->cliente_id,
            'empleado_id' => $request->empleado_id,
            'servicio_id' => $request->servicio_id,
            'fecha'       => $request->fecha,
            'hora'        => $horaInicio,
            'notas'       => $request->notas,
            'estado'      => 'pendiente',
        ]);

        return response()->json(['success' => true, 'cita' => $cita]);
    }

    // --------------------------------
    // Actualizar cita
    // --------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id'  => 'required|exists:clientes,id',
            'empleado_id' => 'nullable|exists:usuarios,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha'       => 'required|date',
            'hora'        => 'required',
            'notas'       => 'nullable|string',
        ]);

        $cita = Cita::findOrFail($id);
        $servicio = Servicios::findOrFail($request->servicio_id);

        if (!$servicio->Activo) {
            return response()->json(['success' => false, 'message' => 'El servicio no está activo.'], 422);
        }

        $horaInicio = date('H:i:s', strtotime($request->hora));
        $duracion = intval($servicio->Duracion);
        $horaFin = date('H:i:s', strtotime($horaInicio . " + {$duracion} minutes"));

        // Horario
        if ($horaInicio < "09:00:00" || $horaFin > "20:00:00") {
            return response()->json(['success'=>false, 'message'=>'Fuera del horario de atención.'], 422);
        }

        // No pasado
        $fechaHora = strtotime($request->fecha . ' ' . $horaInicio);
        if ($fechaHora < time()) {
            return response()->json(['success'=>false, 'message'=>'No puedes agendar en el pasado.'], 422);
        }

        // Empalme cliente (FIX ID AMBIGUO)
        $empCliente = Cita::where('citas.fecha', $request->fecha)
            ->where('citas.cliente_id', $request->cliente_id)
            ->where('citas.id', '<>', $cita->id) // FIX ✔
            ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
            ->get(['citas.hora as inicio_cita','servicios.Duracion'])
            ->contains(function ($c) use ($horaInicio, $horaFin) {
                $inicioExistente = date('H:i:s', strtotime($c->inicio_cita));
                $finExistente = date('H:i:s', strtotime($inicioExistente . " + {$c->Duracion} minutes"));
                return ($horaInicio < $finExistente) && ($horaFin > $inicioExistente);
            });

        if ($empCliente) {
            return response()->json(['success' => false, 'message' => 'El cliente tiene otra cita que se empalma.'], 422);
        }

        // Empalme empleado
        if ($request->empleado_id) {
            if ($this->haySolapamiento($request->fecha, $horaInicio, $horaFin, $request->empleado_id, $cita->id)) {
                return response()->json(['success' => false, 'message' => 'El empleado tiene otra cita en ese horario.'], 422);
            }
        }

        $cita->update([
            'cliente_id'  => $request->cliente_id,
            'empleado_id' => $request->empleado_id,
            'servicio_id' => $request->servicio_id,
            'fecha'       => $request->fecha,
            'hora'        => $horaInicio,
            'notas'       => $request->notas,
        ]);

        return response()->json(['success' => true]);
    }

    // --------------------------------
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();
        return response()->json(['success' => true]);
    }

    // --------------------------------
    // STORE CLIENTE
    // --------------------------------
    public function storeCliente(Request $request)
    {
        $request->validate([
            'servicio' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'notas' => 'nullable|string',
        ]);

        $cliente = Clientes::where('usuario_id', auth()->user()->id)->first();

        if (!$cliente) {
            return redirect()->back()->with('error', 'No se encontró el cliente asociado.');
        }

        $servicio = Servicios::findOrFail($request->servicio);

        if (!$servicio->Activo) {
            return redirect()->back()->withErrors(['servicio'=>'Servicio inactivo'])->withInput();
        }

        $duracion = intval($servicio->Duracion);
        $horaInicio = date('H:i:s', strtotime($request->hora));
        $horaFin = date('H:i:s', strtotime($horaInicio . " + {$duracion} minutes"));

        if ($horaInicio < "09:00:00" || $horaFin > "20:00:00") {
            return redirect()->back()->withErrors(['hora' => 'Fuera del horario'])->withInput();
        }

        if (strtotime($request->fecha . ' ' . $horaInicio) < time()) {
            return redirect()->back()->withErrors(['hora' => 'No puedes agendar en el pasado'])->withInput();
        }

        // Empalme cliente
        $empCliente = Cita::where('fecha', $request->fecha)
            ->where('cliente_id', $cliente->id)
            ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
            ->get(['citas.hora as inicio_cita','servicios.Duracion'])
            ->contains(function ($c) use ($horaInicio, $horaFin) {
                $inicioExistente = date('H:i:s', strtotime($c->inicio_cita));
                $finExistente = date('H:i:s', strtotime($inicioExistente . " + {$c->Duracion} minutes"));
                return ($horaInicio < $finExistente) && ($horaFin > $inicioExistente);
            });

        if ($empCliente) {
            return redirect()->back()->withErrors(['hora'=>'Ya tienes una cita en ese horario'])->withInput();
        }

        Cita::create([
            'cliente_id' => $cliente->id,
            'empleado_id' => null,
            'servicio_id' => $request->servicio,
            'fecha' => $request->fecha,
            'hora' => $horaInicio,
            'notas' => $request->notas,
            'estado' => 'pendiente'
        ]);

        return redirect()->route('dashboard')->with('mensaje', '✨ Cita agendada con éxito.');
    }

    // --------------------------------
    public function cancelar($id)
    {
        $cita = Cita::findOrFail($id);
        $cliente = Clientes::where('usuario_id', auth()->id())->first();

        if (!$cliente || $cita->cliente_id != $cliente->id) {
            return redirect()->back()->with('error', 'No tienes permiso para cancelar.');
        }

        $cita->estado = "cancelada";
        $cita->save();

        return redirect()->back()->with('success', 'Cita cancelada correctamente.');
    }

    // --------------------------------
    // Horas disponibles
    // --------------------------------
    public function getHorasDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'servicio_id' => 'required|exists:servicios,id'
        ]);

        $fecha = $request->fecha;
        $servicio = Servicios::findOrFail($request->servicio_id);

        if (!$servicio->Activo) {
            return response()->json([], 200);
        }

        $duracion = intval($servicio->Duracion);
        $horaInicioDia = "09:00";
        $horaFinDia = "20:00";
        $paso = 30;

        $intervalos = [];
        $horaActual = strtotime($horaInicioDia);

        while ($horaActual <= strtotime($horaFinDia)) {
            $inicio = date('H:i', $horaActual);
            $fin = date('H:i', strtotime("+{$duracion} minutes", $horaActual));

            if (strtotime($fin) <= strtotime($horaFinDia . ':00')) {
                $intervalos[] = ['inicio' => $inicio, 'fin' => $fin];
            }

            $horaActual = strtotime("+{$paso} minutes", $horaActual);
        }

        $citas = Cita::where('fecha', $fecha)
            ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
            ->get(['citas.hora as inicio_cita', 'servicios.Duracion']);

        $disponibles = [];

        foreach ($intervalos as $bloque) {
            $ocupado = false;

            foreach ($citas as $cita) {
                $inicioExistente = date('H:i', strtotime($cita->inicio_cita));
                $finExistente = date('H:i', strtotime($inicioExistente . " + {$cita->Duracion} minutes"));

                if ( ($bloque['inicio'] < $finExistente) && ($bloque['fin'] > $inicioExistente) ) {
                    $ocupado = true;
                    break;
                }
            }

            if (!$ocupado) {
                $fechaHora = strtotime($fecha . ' ' . $bloque['inicio']);
                if ($fechaHora >= strtotime(date('Y-m-d H:i'))) {
                    $disponibles[] = $bloque['inicio'];
                }
            }
        }

        return response()->json($disponibles);
    }
}
