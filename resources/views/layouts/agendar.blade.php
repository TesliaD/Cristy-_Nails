{{-- resources/views/agendar.blade.php --}}
@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')
@php
    // formato para hoy (min)
    $hoy = date('Y-m-d');
@endphp

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-4"
                     style="background: linear-gradient(90deg, #ff7eb3, #ff758c);">
                    <h3 class="mb-0">✨ Agendar tu Cita</h3>
                    <small class="d-block mt-1">Verifica servicio, fecha y hora antes de confirmar</small>
                </div>

                <div class="card-body p-4">

                    @guest
                        <div class="alert alert-warning text-center">
                            ⚠️ Debes <a href="{{ route('login') }}">iniciar sesión</a> para agendar una cita.
                        </div>
                    @endguest

                    @auth
                        {{-- Contenedor de errores generales (populated por JS) --}}
                        <div id="erroresGenerales" class="mb-3" style="display:none;"></div>

                        <form id="formAgendar" method="POST" action="{{ route('agendar.store') }}" novalidate>
                            @csrf

                            <!-- Servicio -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Servicio <span class="text-danger">*</span></label>
                                <select name="servicio" id="servicio" class="form-select rounded-pill" required>
                                    <option value="">-- Selecciona un servicio --</option>
                                    @foreach($servicios as $servicio)
                                        <option value="{{ $servicio->id }}"
                                                data-duracion="{{ $servicio->Duracion ?? 30 }}">
                                            {{ $servicio->Nom_Servicio }} - ${{ $servicio->Precio ?? '0.00' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Selecciona un servicio.</div>
                            </div>

                            <!-- Fecha -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                                <input type="date" name="fecha" id="fecha" class="form-control rounded-pill" required
                                       min="{{ $hoy }}">
                                <div class="form-text small">No puedes escoger domingos ni fechas pasadas.</div>
                                <div class="invalid-feedback" id="fechaError">Selecciona una fecha válida.</div>
                            </div>

                            <!-- Hora dinámica -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Horas disponibles <span class="text-danger">*</span></label>
                                <select name="hora" id="hora" class="form-select rounded-pill" required>
                                    <option value="">-- Selecciona fecha y servicio primero --</option>
                                </select>
                                <div class="invalid-feedback">Selecciona una hora.</div>
                            </div>

                            <!-- Notas -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notas (opcional)</label>
                                <textarea name="notas" id="notas" rows="3"
                                          class="form-control rounded-4"
                                          placeholder="Ejemplo: Prefiero esmalte rojo..."></textarea>
                            </div>

                            <!-- Botón -->
                            <div class="d-grid">
                                <button id="btnEnviar" type="submit" class="btn text-white fw-bold rounded-pill py-2"
                                        style="background: linear-gradient(90deg, #ff758c, #ff7eb3);">
                                    <i class="bi bi-heart-fill"></i> Agendar
                                </button>
                            </div>
                        </form>
                    @endauth

                </div>
            </div>
        </div>
    </div>
</div>

{{-- SWEETALERT DEL MENSAJE DE ÉXITO --}}
@if(session('mensaje'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'success',
                title: 'Cita agendada',
                text: '{{ session("mensaje") }}',
                timer: 1800,
                showConfirmButton: false
            });
        });
    </script>
@endif

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const rutaHoras = "{{ route('citas.horas') }}"; 
    const form = document.getElementById('formAgendar');
    const servicio = document.getElementById('servicio');
    const fecha = document.getElementById('fecha');
    const hora = document.getElementById('hora');
    const notas = document.getElementById('notas');
    const btnEnviar = document.getElementById('btnEnviar');
    const erroresGenerales = document.getElementById('erroresGenerales');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        ?? document.querySelector('input[name="_token"]').value;

    // -------------- UTIL: form validation helpers ----------------
    function marcarValido(el) {
        el.classList.remove('is-invalid');
        el.classList.add('is-valid');
        if (el.nextElementSibling && el.nextElementSibling.classList.contains('invalid-feedback')) {
            el.nextElementSibling.style.display = 'none';
        }
    }
    function marcarInvalido(el, msg) {
        el.classList.remove('is-valid');
        el.classList.add('is-invalid');
        const feedback = el.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.innerText = msg || feedback.innerText;
            feedback.style.display = 'block';
        }
    }

    // -------------- Fecha mínima y bloqueo domingos ----------------
    const hoy = new Date();
    // set min is already provided by server side blade with today's date
    fecha.addEventListener('change', () => {
        erroresGenerales.style.display = 'none';
        // bloquear domingos (0 = domingo)
        const sel = new Date(fecha.value + 'T00:00:00');
        if (!fecha.value) {
            marcarInvalido(fecha, 'Selecciona una fecha');
            return;
        }
        if (sel.getDay() === 0) {
            marcarInvalido(fecha, 'Los domingos no están disponibles. Elige otro día.');
            hora.innerHTML = '<option value="">No disponible</option>';
            return;
        }
        // si fecha es hoy, las horas pasadas serán filtradas cuando se carguen
        marcarValido(fecha);
        cargarHorasDisponibles();
    });

    // -------------- Cargar horas dinámicamente ----------------
    async function cargarHorasDisponibles() {
        hora.innerHTML = '<option>Cargando...</option>';
        if (!fecha.value || !servicio.value) {
            hora.innerHTML = '<option value="">Selecciona fecha y servicio</option>';
            return;
        }

        // petición
        try {
            const params = new URLSearchParams({ fecha: fecha.value, servicio_id: servicio.value });
            const res = await fetch(rutaHoras + '?' + params.toString(), {
                headers: { 'Accept': 'application/json' }
            });

            if (!res.ok) {
                hora.innerHTML = '<option value="">Error al cargar horarios</option>';
                return;
            }

            const data = await res.json();
            // filtrar horas pasadas si la fecha es hoy
            const hoyStr = new Date().toISOString().slice(0,10);
            const ahora = new Date();

            let opciones = data || [];
            if (fecha.value === hoyStr) {
                opciones = opciones.filter(h => {
                    // h expected 'HH:MM' or 'HH:MM:SS' -> parse hours
                    const timeParts = h.split(':');
                    const hh = parseInt(timeParts[0], 10);
                    const mm = parseInt(timeParts[1], 10);
                    const fechaHora = new Date();
                    fechaHora.setHours(hh, mm, 0, 0);
                    return fechaHora > ahora; // only future times
                });
            }

            hora.innerHTML = '';
            if (!opciones.length) {
                hora.innerHTML = '<option value="">No hay horarios disponibles</option>';
                return;
            }

            opciones.forEach(optVal => {
                const opt = document.createElement('option');
                opt.value = optVal;
                opt.text = optVal;
                hora.appendChild(opt);
            });

            // marcar valido
            marcarValido(hora);
        } catch (err) {
            console.error(err);
            hora.innerHTML = '<option value="">Error de red</option>';
        }
    }

    servicio.addEventListener('change', () => {
        marcarValido(servicio);
        if (fecha.value) cargarHorasDisponibles();
    });

    // -------------- Validaciones visuales en inputs ----------------
    // marcar required fields on blur
    [servicio, fecha, hora].forEach(el => {
        el.addEventListener('blur', () => {
            if (!el.value) {
                marcarInvalido(el, 'Campo requerido');
            } else {
                marcarValido(el);
            }
        });
    });

    // -------------- Envío con confirmación + AJAX ----------------
    form.addEventListener('submit', async function (ev) {
        ev.preventDefault();

        // validaciones cliente-side antes de confirmar
        let hasError = false;
        if (!servicio.value) { marcarInvalido(servicio, 'Selecciona un servicio'); hasError = true; }
        if (!fecha.value) { marcarInvalido(fecha, 'Selecciona una fecha'); hasError = true; }
        if (!hora.value) { marcarInvalido(hora, 'Selecciona una hora'); hasError = true; }

        // comprueba domingo otra vez (por si se forzó)
        const selDate = fecha.value ? new Date(fecha.value + 'T00:00:00') : null;
        if (selDate && selDate.getDay() === 0) {
            marcarInvalido(fecha, 'Los domingos no están disponibles');
            hasError = true;
        }

        if (hasError) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Revisa los campos marcados en rojo.',
                toast: false,
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
            });
            return;
        }

        // Confirmación elegante
        const confirm = await Swal.fire({
            title: 'Confirmar cita',
            html: `<strong>Servicio:</strong> ${servicio.options[servicio.selectedIndex].text}<br>
                   <strong>Fecha:</strong> ${fecha.value}<br>
                   <strong>Hora:</strong> ${hora.value}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, agendar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'animate__animated animate__fadeInDown'
            }
        });

        if (!confirm.isConfirmed) return;

        // Disable button and show loading
        btnEnviar.disabled = true;
        const originalText = btnEnviar.innerHTML;
        btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Guardando...';

        // construir payload
        const payload = {
            servicio: servicio.value,
            fecha: fecha.value,
            hora: hora.value,
            notas: notas.value
        };

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            // Si Laravel devuelve validación 422 con JSON
            if (res.status === 422) {
                const json = await res.json();
                const mensajes = (json.errors) ? Object.values(json.errors).flat().join('<br>') : (json.message || 'Errores de validación');
                erroresGenerales.innerHTML = `<div class="alert alert-warning">${mensajes}</div>`;
                erroresGenerales.style.display = 'block';
                Swal.fire({ icon: 'warning', title: 'Errores', html: mensajes });
                btnEnviar.disabled = false;
                btnEnviar.innerHTML = originalText;
                return;
            }

            // intentar parsear JSON success
            const data = await res.json().catch(() => null);

            if (res.ok && data && (data.success || res.status === 200)) {
                // éxito
                await Swal.fire({
                    icon: 'success',
                    title: 'Cita agendada',
                    text: data.message || 'Tu cita se guardó con éxito ✅',
                    timer: 1800,
                    showConfirmButton: false,
                });
                // opcional: redirigir o refrescar para ver cita
                window.location.reload();
            } else {
                // Puede que no retorne JSON pero la página redirija: fallback
                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }
                const msg = (data && data.message) ? data.message : 'No se pudo agendar la cita';
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
                btnEnviar.disabled = false;
                btnEnviar.innerHTML = originalText;
            }

        } catch (err) {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Error de red. Revisa la consola.' });
            btnEnviar.disabled = false;
            btnEnviar.innerHTML = originalText;
        }
    });

    // small UX: prevent selecting Sundays in datepicker by disabling them on calendar when supported
    // additionally we enforce again on form submit / change
    // Also set min to today (server blade already set min attribute)

    // Accessibility: press enter on servicio tries to open hora load
    servicio.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (fecha.value) cargarHorasDisponibles();
        }
    });

}); // DOMContentLoaded
</script>

<!-- Optional tiny animation CSS (uses Animate.css like classes if you include animate.css in your layout) -->
<style>
/* centro y estilo del card, suaves sombras */
.card-header h3 { letter-spacing: 0.2px; }
.is-valid { box-shadow: 0 0 0 0.15rem rgba(46, 204, 113, .12) !important; }
.is-invalid { box-shadow: 0 0 0 0.15rem rgba(255, 99, 71, .12) !important; }

/* erroresGenerales style */
#erroresGenerales .alert { margin-bottom: 0; }

/* boton: pequeño hover */
#btnEnviar:hover { transform: translateY(-1px); transition: .15s ease; }
</style>

@endsection
