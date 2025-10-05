@extends('app')

@section('title', 'Agendar Cita')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-white text-center rounded-top-4" 
                     style="background: linear-gradient(90deg, #ff7eb3, #ff758c);">
                    <h3 class="text-dark">✨ Agendar tu Cita ✨</h3>
                </div>
                <div class="card-body p-4">

                    {{-- Mensaje dinámico --}}
                    @if(session('mensaje'))
                        <div class="alert alert-success text-center">
                            {{ session('mensaje') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('agendar.store') }}">
                        @csrf

                        <!-- Servicio -->
                        <div class="mb-3">
                            <label for="servicio" class="form-label fw-bold">Servicio</label>
                            <select name="servicio" class="form-select rounded-pill" required>
                                <option value="">-- Selecciona un servicio --</option>
                                <option value="1">💅 Manicura - $200</option>
                                <option value="2">🦶 Pedicura - $250</option>
                                <option value="3">🎨 Diseño de Uñas - $300</option>
                                <option value="4">✨ Otros - Desde $150</option>
                            </select>
                        </div>

                        <!-- Fecha -->
                        <div class="mb-3">
                            <label for="fecha" class="form-label fw-bold">Fecha</label>
                            <input type="date" name="fecha" class="form-control rounded-pill" required>
                        </div>

                        <!-- Hora -->
                        <div class="mb-3">
                            <label for="hora" class="form-label fw-bold">Hora</label>
                            <input type="time" name="hora" class="form-control rounded-pill" required>
                        </div>

                        <!-- Notas -->
                        <div class="mb-3">
                            <label for="notas" class="form-label fw-bold">Notas (opcional)</label>
                            <textarea name="notas" rows="3" 
                                class="form-control rounded-4" 
                                placeholder="Ejemplo: Prefiero esmalte rojo..."></textarea>
                        </div>

                        <!-- Botón -->
                        <div class="d-grid">
                            <button type="submit" class="btn text-white fw-bold rounded-pill py-2"
                                    style="background: linear-gradient(90deg, #ff758c, #ff7eb3);">
                                💖 Agendar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

