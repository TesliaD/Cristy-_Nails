<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    //Mostrar lista de servicios
    public function index()
    {
        $servicios = Servicios::all();
        return view('admin.paneladmin', compact('servicios'));
    }

    // Mostrar servicios en la página pública del cliente (DASHBOARD)
    public function mostrarServicios()
    {
        $servicios = Servicios::where('Activo', 1)->get();
        return view('layouts.dashboard', ['servicios' => $servicios]);
    }

    // Mostrar servicios en AGENDAR
    public function mostrarAgendar()
    {
        $servicios = Servicios::where('Activo', 1)->get();
        return view('layouts.agendar', compact('servicios'));
    }

    //Guardar un nuevo servicio
    public function store(Request $request)
    {
        $request->validate([
            'Nom_Servicio' => 'required|string|max:100',
            'Descripcion'  => 'nullable|string',
            'Precio'       => 'required|numeric|min:0',
            'Duracion'     => 'nullable|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->except('imagen');

        // Guardar imagen si existe
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('imagenes_servicios', 'public');
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
            'Precio'       => 'required|numeric|min:0',
            'Duracion'     => 'nullable|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Datos normales
        $servicio->Nom_Servicio = $request->Nom_Servicio;
        $servicio->Descripcion  = $request->Descripcion;
        $servicio->Precio       = $request->Precio;
        $servicio->Duracion     = $request->Duracion;
        $servicio->Activo       = $request->has('Activo');

        // Si viene una nueva imagen...
        if ($request->hasFile('imagen')) {

            // borrar imagen anterior
            if ($servicio->imagen && Storage::disk('public')->exists($servicio->imagen)) {
                Storage::disk('public')->delete($servicio->imagen);
            }

            // subir nueva
            $servicio->imagen = $request->file('imagen')->store('imagenes_servicios', 'public');
        }

        $servicio->save();

        return redirect()->back()->with('success', 'Servicio actualizado correctamente.');
    }

    //Eliminar Servicio
    public function destroy($id)
    {
        $servicio = Servicios::findOrFail($id);

        // borrar imagen también
        if ($servicio->imagen && Storage::disk('public')->exists($servicio->imagen)) {
            Storage::disk('public')->delete($servicio->imagen);
        }

        $servicio->delete();

        return redirect()->back()->with('success', 'Servicio eliminado correctamente.');
    }
}
