<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Servicios;
use App\Models\Cita;
use PHPUnit\Framework\Constraint\Operator;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 🔹 Mostrar formulario de login
    public function showLogin()
    {
        return view('layouts.login'); 
    }

    // 🔹 Iniciar sesión
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirigir según el rol
            switch ($user->rol) {
                case 'admin':
                    return redirect()->route('paneladmin');
                case 'empleado':
                    return redirect()->route('panelempleado');
                case 'cliente':
                    return redirect()->route('panelclientes'); 
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => '⚠️ Rol no reconocido']);
            }
        }

        // Si las credenciales son incorrectas
        return back()->withErrors([
            'email' => '⚠️ Credenciales incorrectas',
        ]);
    }

    // 🔹 Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // 🔹 Mostrar formulario de registro
    public function mostrarRegistro()
    {
        return view('layouts.registro');
    }

    // 🔹 Registrar nuevos usuarios
    public function registrarUsuario(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Crear usuario (tabla: usuarios)
        $user = User::create([
            'usuario' => $request->usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'cliente',
        ]);

        // Crear registro en tabla clientes relacionado
        Clientes::create([
            'usuario_id' => $user->id,
            'nombre' => $request->input('cliente_nombre', $request->nombre),
            'telefono' => $request->input('telefono'),
            'direccion' => $request->input('direccion'),
        ]);

        // Iniciar sesión automáticamente después de registrarse
        Auth::login($user);

        return redirect()->route('panelclientes') // 👈 también redirige al panel del cliente
            ->with('success', '¡Bienvenido! Tu cuenta se creó correctamente.');
    }

    //Mostrar panel del cliente con sus datos
    public function mostrarpanelclientes()
    {
        $user = Auth::user();

        // Buscar el cliente asociado al usuario
        $cliente = Clientes::where('usuario_id', $user->id)->first();

        // Enviar datos a la vista
        return view('clientes.panelclientes', compact('user', 'cliente'));
    }

    public function mostrarpaneladmin()
    {
        $user = Auth::user();
        $admin = $user;

        // 🔹 Cargar todos los servicios
        $servicios = Servicios::all();

        // 🔹 Cargar todos los clientes junto con su usuario
        $clientes = Clientes::with('usuario')->get();

        // 🔹 Cargar todos los empleados (usuarios con rol empleado)
        $empleados = User::where('rol', 'empleado')->get();

        // 🔹 Cargar todas las citas con sus relaciones
        $citas = \App\Models\Cita::with(['cliente', 'servicio', 'empleado'])
            ->get();

        // 🔹 Preparar datos para el calendario (FullCalendar)
        $citasData = $citas->map(function ($cita) {
            return [
                'id' => $cita->id,
                'title' => ($cita->servicio->Nom_Servicio ?? 'Sin servicio') . ' - ' . ($cita->cliente->nombre ?? 'Sin cliente'),
                'start' => $cita->fecha . 'T' . $cita->hora,
                'hora' => $cita->hora,
                'fecha' => $cita->fecha,
                'cliente_id' => $cita->cliente_id,
                'servicio_id' => $cita->servicio_id,
                'empleado_id' => $cita->empleado_id,
                'notas' => $cita->notas,
                'backgroundColor' => $cita->estado == 'cancelada' ? '#ccc' : '#9ef5b0',
            ];
        });

         // Zona horaria de Arizona para las citas de hoy
        $zona = 'America/Phoenix';
        $hoy = Carbon::today($zona);

        // Traer todas las citas del día
        $citasHoy = Cita::with(['cliente.usuario', 'servicio'])
            ->whereDate('fecha', $hoy)
            ->orderBy('hora', 'asc')
            ->get();

        // Traer solo los clientes que tienen citas hoy
        $clientesHoy = Clientes::whereHas('citas', function ($q) use ($hoy) {
            $q->whereDate('fecha', $hoy);
        })
        ->with('usuario')
        ->get();

        // Contar totales
        $totalClientes = $clientesHoy->count();      
        $totalCitas = $citasHoy->count();      
        $totalServicios = Servicios::count();
        $totalIngresos = 12;

        return view('admin.paneladmin', compact(
            'user',
            'admin',
            'servicios',
            'clientes',
            'empleados',
            'citas',
            'citasData',
            'totalClientes',
            'totalCitas',
            'totalServicios',
            'totalIngresos'
        ));
    }

    public function mostrarpanelempleado(){
        $user = Auth::user();
        return view('empleados.panelempleados', compact('user', 'empleado'));
    }
}
