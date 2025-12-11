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

            $clientesAtendidos = Clientes::whereHas('citas', function ($q) use ($inicio, $fin) {
                $q->whereBetween('fecha', [$inicio, $fin])
                  ->where('estado', 'completada');
            })->get();

            $clientesNuevos = Clientes::whereBetween('created_at', [$inicio, $fin])->get();

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

            $data = compact(
                'inicio',
                'fin',
                'citasAtendidas',
                'citasCanceladas',
                'citasPorEstatus',
                'citasPorEmpleado',
                'citasArealizar'
            );
        }

        // ---------------------------------------------------------------------
        // REPORTE DE SERVICIOS
        // ---------------------------------------------------------------------
        if ($tipo === 'servicios') {

            $serviciosRealizados = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->with(['servicio', 'empleado'])
                ->get();

            $serviciosMasHechos = Cita::selectRaw('servicio_id, COUNT(*) as total')
                ->whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->groupBy('servicio_id')
                ->orderByDesc('total')
                ->with('servicio')
                ->get();

            $todosServicios = Servicios::all();
            $serviciosMenosHechos = $todosServicios->map(function($servicio) use ($inicio, $fin) {
                $servicio->total = Cita::where('servicio_id', $servicio->id)
                    ->where('estado', 'completada')
                    ->whereBetween('fecha', [$inicio, $fin])
                    ->count();
                return $servicio;
            });

            $minTotal = $serviciosMenosHechos->min('total');
            $serviciosMenosHechos = $serviciosMenosHechos->filter(fn($s) => $s->total == $minTotal);

            $data = compact(
                'inicio',
                'fin',
                'serviciosRealizados',
                'serviciosMasHechos',
                'serviciosMenosHechos'
            );
        }

        // ---------------------------------------------------------------------
        // REPORTE DE INGRESOS
        // ---------------------------------------------------------------------
        if ($tipo === 'ingresos') {

            // Total generado por servicios (precio desde tabla servicios)
            $ingresosTotales = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
                ->sum('servicios.Precio');

            // ==================================================
            // SUMA DE COSTO EXTRA (SEA JSON O TEXTO PLANO)
            // ==================================================
            $costoExtraTotal = 0;

            $notas = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->pluck('notas');

            foreach ($notas as $nota) {

                if (!$nota) continue;

                // 1️⃣ INTENTAR DECODIFICAR JSON
                $json = json_decode($nota, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($json['extra'])) {
                    $costoExtraTotal += floatval($json['extra']);
                    continue;
                }

                // 2️⃣ SI NO ES JSON → TEXTO PLANO
                if (preg_match('/extra\s*[:=]?\s*(\d+(?:\.\d+)?)/i', $nota, $match)) {
                    $costoExtraTotal += floatval($match[1]);
                }
            }


            $ingresosTotales += $costoExtraTotal;

            // Por empleado (igual que antes)
            $ingresosPorEmpleado = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
                ->selectRaw('empleado_id, SUM(servicios.Precio) as total')
                ->groupBy('empleado_id')
                ->with('empleado')
                ->get();

            // Por servicio (igual que antes)
            $ingresosPorServicio = Cita::whereBetween('fecha', [$inicio, $fin])
                ->where('estado', 'completada')
                ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
                ->selectRaw('servicio_id, SUM(servicios.Precio) as total')
                ->groupBy('servicio_id')
                ->with('servicio')
                ->get();

            $data = compact(
                'inicio',
                'fin',
                'ingresosTotales',
                'costoExtraTotal',
                'ingresosPorEmpleado',
                'ingresosPorServicio'
            );
        }

        // ---------------------------------------------------------------------
        // Renderizar PDF
        // ---------------------------------------------------------------------
        $nombrePDF = "reporte_{$tipo}_" . time() . ".pdf";
        $ruta = "reportes/$nombrePDF";

        $pdf = Pdf::loadView("reportes.$tipo", $data);
        Storage::disk('public')->put($ruta, $pdf->output());

        DB::table('reportes')->insert([
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
