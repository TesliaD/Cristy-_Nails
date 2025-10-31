<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Solo para mostrar la vista, sin datos reales
        $user = Auth::user();

        return view('empleados.panelempleados', compact('user'));
    }
}
