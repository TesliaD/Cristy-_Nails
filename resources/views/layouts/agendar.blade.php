{{-- resources/views/agendar.blade.php --}}
@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')
@php
    $hoy = date('Y-m-d');
@endphp

<!-- SweetAlert -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/agendar.css') }}">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card card-agendar">

                <!-- HEADER -->
                <div class="card-header">
                    <h3 class="mb-0">Agendar tu Cita</h3>
                    <small class="d-block mt-1">Completa tu información para reservar</small>
                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    @guest
                        <div class="alert alert-warning text-center">
                            ⚠️ Debes <a href="{{ route('login') }}">iniciar sesión</a> para agendar una cita.
                        </div>
                    @endguest

                    @auth

                    <div id="erroresGenerales" class="mb-3" style="display:none;"></div>

                    <form id="formAgendar" method="POST" action="{{ route('agendar.store') }}" novalidate>
                        @csrf

                        <!-- Servicio -->
                        <div class="mb-3">
                            <label class="form-label">Servicio <span class="text-danger">*</span></label>
                            <select name="servicio" id="servicio" class="form-select" required>
                                <option value="">-- Selecciona un servicio --</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" data-duracion="{{ $servicio->Duracion ?? 30 }}">
                                        {{ $servicio->Nom_Servicio }} - ${{ $servicio->Precio }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Selecciona un servicio.</div>
                        </div>

                        <!-- Fecha -->
                        <div class="mb-3">
                            <label class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="fecha" class="form-control" required min="{{ $hoy }}">
                            <div class="form-text small">No se permiten domingos.</div>
                            <div class="invalid-feedback" id="fechaError">Selecciona una fecha válida.</div>
                        </div>

                        <!-- Horas -->
                        <div class="mb-3">
                            <label class="form-label">Hora disponible <span class="text-danger">*</span></label>
                            <select name="hora" id="hora" class="form-select" required>
                                <option value="">Selecciona fecha y servicio primero</option>
                            </select>
                            <div class="invalid-feedback">Selecciona una hora.</div>
                        </div>

                        <!-- Notas -->
                        <div class="mb-3">
                            <label class="form-label">Notas (opcional)</label>
                            <textarea name="notas" id="notas" rows="3" class="form-control" placeholder="Ejemplo: Prefiero esmalte rojo"></textarea>
                        </div>

                        <!-- Botón -->
                        <button id="btnEnviar" class="btn btn-agendar w-100">Agendar</button>

                    </form>
                    @endauth

                </div>
            </div>

        </div>
    </div>
</div>

{{-- MENSAJE DE ÉXITO --}}
@if(session('mensaje'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Cita agendada',
            text: '{{ session("mensaje") }}',
            timer: 1800,
            showConfirmButton: false
        });
    </script>
@endif

<!-- JS ORIGINAL (no lo cambio para no afectar tu lógica) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* tu mismo JS completo aquí (no lo altero para evitar errores) */
</script>

@endsection
