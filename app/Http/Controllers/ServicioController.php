<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    //Mostrar lista de servicios
    public function index()
    {
        $servicios = Servicios::all();
        return view('admin.paneladmin', compact('servicios'));
    }

    // Mostrar servicios en la página pública del cliente
    public function mostrarServicios()
    {
        // obtiene los servicios activos
        $servicios = Servicios::where('Activo', 1)->get();

        // devuelve la vista dashboard y le pasa los servicios
        return view('layouts.dashboard', ['servicios' => $servicios]);
    }

    //Guardar un nuevo servicio
    public function store(Request $request)
    {
        $request->validate([
            'Nom_Servicio' => 'required|string|max:100',
            'Descripcion'  => 'nullable|string',
            'Precio'       => 'required|numeric|min:0',
            'Duracion'     => 'nullable|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // Aumenté a 5MB
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $ruta = $imagen->storeAs('servicios', $nombreArchivo, 'public');
            $data['imagen'] = $ruta;
        }

        Servicios::create($data);

        return redirect()->back()->with('success', 'Servicio agregado correctamente.');
    }


    //Actualizar Servicio
    public function update(Request $request, $id)
    {
        $servicio = Servicios::findOrFail($id);

        $request->validate([
            'Nom_Servicio' => 'required|string|max:100',
            'Descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'Duracion'     => 'nullable|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $servicio->Nom_Servicio = $request->Nom_Servicio;
        $servicio->Descripcion  = $request->Descripcion;
        $servicio->Precio       = $request->precio;
        $servicio->Duracion     = $request->Duracion;
        $servicio->Activo       = $request->has('activo');

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('servicios', 'public');
            $servicio->imagen = $path;
        }

        $servicio->save();

        return redirect()->back()->with('success', 'Servicio actualizado correctamente.');
    }

    //Eliminar Servicio
    public function destroy($id)
    {
        $servicio = Servicios::findOrFail($id);
        $servicio->delete();

        return redirect()->back()->with('success', 'Servicio eliminado correctamente.');
    }
}
