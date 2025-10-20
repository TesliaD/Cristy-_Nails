<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendarController extends Controller
{
    public function store(Request $request)
    {
        // Aquí irá tu lógica para guardar la cita/agendamiento.
        // Por ahora, solo devolvemos algo para probar:
        return response()->json([
            'message' => 'Formulario de agendar recibido correctamente.',
            'data' => $request->all()
        ]);
    }
}
