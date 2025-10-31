<?php

namespace App\Http\Controllers;
use App\Models\Servicios;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnCallback;

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

    //Metodo para guardar un nuevo servicio
    public function store(Request $request)
    {
        $request->validate([
            'Nom_Servicio'=>'required|string|max:100',
            'Descripcion'=>'nullable|string',
            'Precio'=>'required|numeric|min:0',
            'Duracion'=>'nullable|integer|min:0'
        ]);

        //Guarda los datos
        $data=$request->all();

        //Si el usuario subió una imagen
        if($request->hasFile('imagen'))
        {
            $path=$request->file('imagen')->store('servicios','public');
            $data['imagen']=$path;
        }

        Servicios::create($data);
        return redirect()->back()->with('success','Servicio agregado');
    }

    //Editar Servicio
    public function update(Request $request, $id)
    {
        $servicio =Servicios::findOrFail($id);

        $request->validate([
            'Nom_Servicio'=>'required|string|max:100',
            'Descripcion' => 'nullable|string',
            'Precio' => 'required|numeric|min:0',
            'Duracion' => 'nullable|integer|min:0',
        ]);

        $servicio->update($request->all());
        return redirect()->back()->with('success', 'Servicio actualizado correctamente.');
    }

    //Eliminar Servicio
    public function destroy($id)
    {
        $servicio = Servicios::findOrFail($id);
        $servicio->delete();

        return redirect()->back()->with('success','Servicio eliminado correctamente');
    }
    
}
