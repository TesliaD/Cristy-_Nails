@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')

<div class="container my-5">

    {{-- Mostrar alerta si NO ha iniciado sesión --}}
    @guest
        <div class="alert alert-danger text-center p-4 rounded-4 shadow">
            <h4 class="fw-bold">⚠️ Debes iniciar sesión para agendar una cita</h4>
            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                Iniciar Sesión
            </a>
        </div>
    @endguest


    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-white text-center rounded-top-4"
                     style="background: linear-gradient(90deg, #ff7eb3, #ff758c);">
                    <h3 class="text-dark">✨ Agendar tu Cita ✨</h3>
                </div>

                <div class="card-body p-4">

                    {{-- Solo mostrar el formulario si está autenticado --}}
                    @auth

                        @if(session('mensaje'))
                            <div class="alert alert-success text-center">
                                {{ session('mensaje') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('agendar.store') }}">
                            @csrf

                            <!-- Servicio -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Servicio</label>
                                <select name="servicio" id="servicio" class="form-select rounded-pill" required>
                                    <option value="">-- Selecciona un servicio --</option>

                                    @foreach($servicios as $servicio)
                                        <option value="{{ $servicio->id }}">
                                            {{ $servicio->Nom_Servicio }} - ${{ $servicio->Precio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fecha -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha</label>
                                <input type="date" name="fecha" id="fecha" class="form-control rounded-pill" required>
                            </div>

                            <!-- Hora dinámica -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Horas disponibles</label>
                                <select name="hora" id="hora" class="form-select rounded-pill" required>
                                    <option value="">-- Selecciona fecha y servicio primero --</option>
                                </select>
                            </div>

                            <!-- Notas -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notas (opcional)</label>
                                <textarea name="notas" rows="3"
                                          class="form-control rounded-4"
                                          placeholder="Ejemplo: Prefiero esmalte rojo..."></textarea>
                            </div>

                            <!-- Botón -->
                            <div class="d-grid">
                                <button type="submit" class="btn text-white fw-bold rounded-pill py-2"
                                        style="background: linear-gradient(90deg, #ff758c, #ff7eb3);">
                                        <i class="bi bi-heart-fill"></i> Agendar
                                </button>
                            </div>

                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {

                                const fecha = document.getElementById('fecha');
                                const servicio = document.getElementById('servicio');
                                const hora = document.getElementById('hora');

                                async function cargarHoras() {
                                    if (!fecha.value || !servicio.value) {
                                        hora.innerHTML = '<option>-- Selecciona fecha y servicio --</option>';
                                        return;
                                    }

                                    const url = "{{ route('citas.horas') }}";
                                    const params = new URLSearchParams({
                                        fecha: fecha.value,
                                        servicio_id: servicio.value
                                    });

                                    let response = await fetch(url + "?" + params);
                                    let data = await response.json();

                                    hora.innerHTML = "";

                                    if (data.length === 0) {
                                        hora.innerHTML = '<option value="">No hay horarios disponibles</option>';
                                        return;
                                    }

                                    data.forEach(h => {
                                        hora.innerHTML += `<option value="${h}">${h}</option>`;
                                    });
                                }

                                fecha.addEventListener('change', cargarHoras);
                                servicio.addEventListener('change', cargarHoras);

                            });
                        </script>


                    @endauth

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
