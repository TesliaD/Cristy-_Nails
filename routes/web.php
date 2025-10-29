<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientePanelController;
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
});


// -----------------------------------------------------------
// 🏠 GRUPO DE VISTAS GENERALES (Dashboard, Inicio, etc.)
// -----------------------------------------------------------
Route::prefix('panel')->group(function () {

    Route::get('/dashboard', function () {
        return view('layouts.dashboard');
    })->name('dashboard');

    // AGENDAR
    Route::get('/agendar', function () {
        return view('layouts.agendar');
    })->name('agendar');

    Route::get('sobrenosotros', function(){
        return view('layouts.sobrenosotros');
    })->name(name:'sobrenosotros');
});


// -----------------------------------------------------------
// 🚀 RUTA PRINCIPAL
// -----------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('dashboard');
});
