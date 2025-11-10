<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientePanelController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\CitaController;

use function PHPUnit\Framework\callback;


// -----------------------------------------------------------
// 🧩 GRUPO DE AUTENTICACIÓN (Login / Registro)
// -----------------------------------------------------------
Route::prefix('auth')->group(function () {
    // LOGIN
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post'); // 👈 Añadido el name

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // REGISTRO
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrarUsuario'])->name('registro.guardar');
});

//Panel para clientes

Route::middleware('auth')->prefix('cliente')->group(function () {
    Route::get('/panel', [AuthController::class, 'mostrarpanelclientes'])->name('panelclientes');
    Route::post('/panel/update', [ClientePanelController::class, 'update'])->name('panelcliente.update');
});

//Panel para Administrador
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/paneladmin', [AuthController::class, 'mostrarpaneladmin'])->name('paneladmin');

    //Rutas para servicios en el Panel de administrador
    Route::get('/paneladmin/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::post('/paneladmin/servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::put('/paneladmin/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::delete('/paneladmin/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

    //Rutas para Clientes en el panel de Administrador
    Route::post('/paneladmin/clientes', [AdminPanelController::class,'clientes_store'])->name('clientes.store');
    Route::delete('/paneladmin/clientes/{id}', [AdminPanelController::class, 'clientes_destroy'])->name('clientes.destroy');
    Route::get('/paneladmin/clientes/{id}/edit', [AdminPanelController::class, 'clientes_edit'])->name('clientes.edit');
    Route::put('/paneladmin/clientes/{id}', [AdminPanelController::class, 'clientes_update'])->name('clientes.update');

    //Rutas para Citas en el panel de Administrador
    Route::get('paneladmin/citas',[CitaController::class, 'index'])->name('citas.index');
    Route::post('paneladmin/citas', [CitaController::class, 'store'])->name('citas.store');
    Route::delete('paneladmin/citas/{id}',[CitaController::class,'destroy'])->name('citas.destroy');


});

//Panel para Empleados

Route::middleware('auth')->prefix('empleado')->group(function () {
    Route::get('/panelempleado', [EmpleadoController::class, 'index'])->name('panelempleado');

    // Calendario - Jalar Cita
    Route::get('/citasempleado', [EmpleadoController::class, 'citasEmpleado'])->name('empleado.citas');

});



// Página pública donde se listan los servicios
// -----------------------------------------------------------
// 🚀 RUTA PRINCIPAL — Muestra los servicios dinámicos
// -----------------------------------------------------------
// -----------------------------------------------------------
// 🏠 GRUPO DE VISTAS GENERALES (Panel Interno)
// -----------------------------------------------------------
Route::prefix('panel')->group(function () {

    Route::get('/dashboard', [ServicioController::class, 'mostrarServicios'])->name('dashboard');
    Route::get('/agendar', [ServicioController::class, 'mostrarServicios'])->name('agendar');
  
    Route::get('/agendar', function () {
        return view('layouts.agendar');
    })->name('agendar');

    Route::get('/sobrenosotros', function () {
        return view('layouts.sobrenosotros');
    })->name('sobrenosotros');
});


