<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\Servicios;
use App\Models\Clientes;
use App\Http\Controllers\Log;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Solo para mostrar la vista, sin datos reales
        $user = Auth::user();

        return view('empleados.panelempleados', compact('user'));
    }
    // Crear empleado
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
            'email'   => 'required|email|unique:usuarios,email', 
            'rol'     => 'required|string',
            'password' => 'required|min:6'
        ]);

        User::create([
            'usuario' => $request->usuario,
            'email'   => $request->email,
            'rol'     => $request->rol,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Empleado creado correctamente');
    }


    // Actualizar empleado
    public function update(Request $request, $id)
    {
        $empleado = User::findOrFail($id);

        $request->validate([
            'usuario' => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $empleado->id,
            'rol'     => 'required|string',
        ]);

        $empleado->usuario = $request->usuario;
        $empleado->email   = $request->email;
        $empleado->rol     = $request->rol;

        if ($request->password) {
            $empleado->password = Hash::make($request->password);
        }

        $empleado->save();

        return redirect()->back()->with('success', 'Empleado actualizado correctamente');
    }

    // Eliminar empleado
    public function destroy($id)
    {
        $empleado = User::findOrFail($id);
        $empleado->delete();

        return redirect()->back()->with('success', 'Empleado eliminado');
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
                ->orderBy('hora', 'asc')
                ->get();

    // Contar totales
    $totalClientes = Clientes::whereHas('citas', function ($query) use ($empleadoId, $inicio, $fin) {
    $query->where('empleado_id', $empleadoId)
          ->whereBetween('fecha', [$inicio, $fin]);})->distinct()->count();
    $totalCitas = $citas->count();      // Solo las citas de hoy
    $totalServicios = Servicios::count();


    // Pasar todo a la vista
    return view('empleados.panelempleados', compact('citas', 'totalClientes', 'totalCitas', 'totalServicios'));
    }

}
