<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\Servicios;
use App\Models\Clientes;
use App\Http\Controllers\Log;
use Carbon\Carbon;
//use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Solo para mostrar la vista, sin datos reales
        $user = Auth::user();

        return view('empleados.panelempleados', compact('user'));
    }

    public function citasEmpleado()
    {
        // Cargar con relaciones
        $citas = Cita::with(['cliente', 'servicio'])->get();

        $eventos = [];

        foreach ($citas as $cita) {
            // valores calculados antes de construir el array (sin usar ?? dentro de la interpolación)
            $cliente = isset($cita->cliente->nombre) ? $cita->cliente->nombre : 'Sin cliente';
            $fecha   = $cita->fecha;
            $hora    = isset($cita->hora) ? $cita->hora : '';
            $servicio = isset($cita->servicio->Nom_Servicio) ? $cita->servicio->Nom_Servicio : 'Sin servicio';

            // notas: primero la nota de la cita, si no existe, la del servicio, si no, texto por defecto
            if (isset($cita->notas) && $cita->notas !== '') {
                $notas = $cita->notas;
            } elseif (isset($cita->servicio->Descripcion) && $cita->servicio->Descripcion !== '') {
                $notas = $cita->servicio->Descripcion;
            } else {
                $notas = 'Sin notas';
            }

            $eventos[] = [
                'cliente'  => $cliente,
                'fecha'    => $fecha,
                'hora'     => $hora,
                'servicio' => $servicio,
                'notas'    => $notas,
            ];
        }

        return response()->json($eventos);
    }

    public function panelMisCitas()
    {
    $empleadoId = auth()->id();

    // Zona horaria de Arizona para las citas de hoy
    $zona = 'America/Phoenix';
    $inicio = \Carbon\Carbon::today($zona)->startOfDay();
    $fin = \Carbon\Carbon::today($zona)->endOfDay();

    // Traer las citas del día
    $citas = Cita::with(['cliente.usuario', 'servicio'])
                ->where('empleado_id', $empleadoId)
                ->whereBetween('fecha', [$inicio, $fin])
                ->get();

    // Contar totales
    $totalClientes = Clientes::has('citas')->count();  // Todos los clientes
    $totalCitas = $citas->count();      // Solo las citas de hoy
    $totalServicios = Servicios::count();

    // Pasar todo a la vista
    return view('empleados.panelempleados', compact('citas', 'totalClientes', 'totalCitas', 'totalServicios'));
    }

}
