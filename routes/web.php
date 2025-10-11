<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// -----------------------------------------------------------
// 🧩 GRUPO DE AUTENTICACIÓN (Login / Registro)
// -----------------------------------------------------------
Route::prefix('auth')->group(function () {

    // LOGIN
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // REGISTRO
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrarUsuario'])->name('registro.guardar');
});

// -----------------------------------------------------------
// 🏠 GRUPO DE VISTAS GENERALES (Dashboard, Inicio, etc.)
// -----------------------------------------------------------
Route::prefix('panel')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // AGENDAR
    Route::get('/agendar', function () {
        return view('agendar');
    })->name('agendar');

    Route::get('sobrenosotros', function(){
        return view('sobrenosotros');
    })->name(name:'sobrenosotros');
});


// -----------------------------------------------------------
// 🚀 RUTA PRINCIPAL
// -----------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('dashboard');
});
