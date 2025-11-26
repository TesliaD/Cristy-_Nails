<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Clientes;
use App\Models\Servicios;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function generarReporte(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tipo = $request->tipo;

        // ---------------------------------------------------------------------
        // REPORTE DE CLIENTES
        // ---------------------------------------------------------------------
        if ($tipo === 'clientes') {

            // Clientes que se atendieron
            $clientesAtendidos = Clientes::whereHas('citas', function ($q) use ($inicio, $fin) {
                $q->whereBetween('fecha', [$inicio, $fin])
                  ->where('estado', 'completada');
            })->get();

            // Clientes nuevos
            $clientesNuevos = Clientes::whereBetween('created_at', [$inicio, $fin])->get();

            // Total de citas por cliente
            $citasPorCliente = Clientes::withCount(['citas' => function ($q) use ($inicio, $fin) {
                $q->whereBetween('fecha', [$inicio, $fin]);
            }])->get();

            $data = compact('inicio', 'fin', 'clientesAtendidos', 'clientesNuevos', 'citasPorCliente');
        }

        // ---------------------------------------------------------------------
        // REPORTE DE CITAS
        // ---------------------------------------------------------------------
        if ($tipo === 'citas') {

            $citasAtendidas = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->with(['cliente', 'empleado', 'servicio'])
                ->get();

            $citasCanceladas = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'cancelada')
                ->with(['cliente', 'empleado', 'servicio'])
                ->get();

            $citasArealizar = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'pendiente')
                ->with(['cliente', 'empleado', 'servicio'])
                ->get();

            $citasPorEstatus = Cita::selectRaw('estado, COUNT(*) as total')
                ->whereBetween('fecha', [$inicio, $fin])
                ->groupBy('estado')
                ->get();

            $citasPorEmpleado = Cita::selectRaw('empleado_id, COUNT(*) as total')
                ->whereBetween('fecha', [$inicio, $fin])
                ->groupBy('empleado_id')
                ->with('empleado')
                ->get();

            $data = compact('inicio', 'fin', 'citasAtendidas', 'citasCanceladas', 'citasPorEstatus', 'citasPorEmpleado', 'citasArealizar');
        }

        // REPORTE DE SERVICIOS
        // ---------------------------------------------------------------------
        if ($tipo === 'servicios') {

            $serviciosRealizados = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->with(['servicio', 'empleado'])
                ->get();

            // Servicios más hechos (igual que antes)
            $serviciosMasHechos = Cita::selectRaw('servicio_id, COUNT(*) as total')
                ->whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->groupBy('servicio_id')
                ->orderByDesc('total')
                ->with('servicio')
                ->get();

            // Servicios menos hechos (incluye los que tienen 0)
            $todosServicios = Servicios::all();
            $serviciosMenosHechos = $todosServicios->map(function($servicio) use ($inicio, $fin) {
                $servicio->total = Cita::where('servicio_id', $servicio->id)
                    ->where('estado', 'completada')
                    ->whereBetween('fecha', [$inicio, $fin])
                    ->count();
                return $servicio;
            });

            // Filtrar solo los que tienen el mínimo total
            $minTotal = $serviciosMenosHechos->min('total');
            $serviciosMenosHechos = $serviciosMenosHechos->filter(fn($s) => $s->total == $minTotal);

            $data = compact('inicio', 'fin', 'serviciosRealizados', 'serviciosMasHechos', 'serviciosMenosHechos');
        }


        // ---------------------------------------------------------------------
        // REPORTE DE INGRESOS
        // ---------------------------------------------------------------------
        if ($tipo === 'ingresos') {

            // Total generado (sumando precios del servicio)
            $ingresosTotales = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
                ->sum('servicios.Precio');

            // Por empleado
            $ingresosPorEmpleado = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
                ->selectRaw('empleado_id, SUM(servicios.Precio) as total')
                ->groupBy('empleado_id')
                ->with('empleado')
                ->get();

            // Por servicio
            $ingresosPorServicio = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
                ->selectRaw('servicio_id, SUM(servicios.Precio) as total')
                ->groupBy('servicio_id')
                ->with('servicio')
                ->get();

            $data = compact('inicio', 'fin', 'ingresosTotales', 'ingresosPorEmpleado', 'ingresosPorServicio');
        }

        // ---------------------------------------------------------------------
        // Renderizar PDF
        // ---------------------------------------------------------------------
        $nombrePDF = "reporte_{$tipo}_" . time() . ".pdf";
        $ruta = "reportes/$nombrePDF";


        $pdf = Pdf::loadView("reportes.$tipo", $data);
        Storage::disk('public')->put($ruta, $pdf->output());

        // Guardar en BD (tabla reportes)
        \DB::table('reportes')->insert([
            'tipo' => $tipo,
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
            'ruta_pdf' => $ruta,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'url' => asset("storage/$ruta"),
            'tipo' => $tipo,
            'inicio' => $inicio,
            'fin' => $fin
        ]);




    }
}
