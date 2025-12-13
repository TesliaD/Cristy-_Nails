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

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ===============================
       VARIABLES GLOBALES
    =============================== */
    const rutaHoras = "{{ route('citas.horas') }}";

    const form      = document.getElementById('formAgendar');
    const servicio  = document.getElementById('servicio');
    const fecha     = document.getElementById('fecha');
    const hora      = document.getElementById('hora');
    const notas     = document.getElementById('notas');
    const btnEnviar = document.getElementById('btnEnviar');
    const errores   = document.getElementById('erroresGenerales');

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ??
        document.querySelector('input[name="_token"]').value;


    /* ===============================
       HELPERS VISUALES
    =============================== */
    const marcarValido = (el) => {
        el.classList.remove('is-invalid');
        el.classList.add('is-valid');
    };

    const marcarInvalido = (el, msg = '') => {
        el.classList.remove('is-valid');
        el.classList.add('is-invalid');
        if (el.nextElementSibling?.classList.contains('invalid-feedback')) {
            el.nextElementSibling.innerText = msg || el.nextElementSibling.innerText;
            el.nextElementSibling.style.display = 'block';
        }
    };


    /* ===============================
       FECHA → BLOQUEAR DOMINGOS
    =============================== */
    fecha.addEventListener('change', () => {
        errores.style.display = 'none';

        if (!fecha.value) {
            marcarInvalido(fecha, 'Selecciona una fecha');
            return;
        }

        const seleccionada = new Date(fecha.value + 'T00:00:00');

        if (seleccionada.getDay() === 0) {
            marcarInvalido(fecha, 'Los domingos no están disponibles');
            hora.innerHTML = '<option value="">No disponible</option>';
            return;
        }

        marcarValido(fecha);
        cargarHoras();
    });


    /* ===============================
       CARGAR HORAS DISPONIBLES
    =============================== */
    async function cargarHoras() {

        if (!fecha.value || !servicio.value) {
            hora.innerHTML = '<option value="">Selecciona fecha y servicio</option>';
            return;
        }

        hora.innerHTML = '<option>Cargando...</option>';

        try {
            const params = new URLSearchParams({
                fecha: fecha.value,
                servicio_id: servicio.value
            });

            const res = await fetch(`${rutaHoras}?${params}`);
            if (!res.ok) throw new Error('Error servidor');

            let horas = await res.json();

            /* Filtrar horas pasadas si es hoy */
            const hoy = new Date().toISOString().slice(0, 10);
            if (fecha.value === hoy) {
                const ahora = new Date();
                horas = horas.filter(h => {
                    const [hh, mm] = h.split(':');
                    const fh = new Date();
                    fh.setHours(hh, mm, 0);
                    return fh > ahora;
                });
            }

            hora.innerHTML = '';

            if (!horas.length) {
                hora.innerHTML = '<option value="">No hay horarios disponibles</option>';
                return;
            }

            horas.forEach(h => {
                const opt = document.createElement('option');
                opt.value = h;
                opt.textContent = h;
                hora.appendChild(opt);
            });

            marcarValido(hora);

        } catch (err) {
            console.error(err);
            hora.innerHTML = '<option value="">Error al cargar horarios</option>';
        }
    }


    servicio.addEventListener('change', () => {
        marcarValido(servicio);
        if (fecha.value) cargarHoras();
    });


    /* ===============================
       VALIDACIÓN EN BLUR
    =============================== */
    [servicio, fecha, hora].forEach(el => {
        el.addEventListener('blur', () => {
            !el.value ? marcarInvalido(el, 'Campo requerido') : marcarValido(el);
        });
    });


    /* ===============================
       ENVÍO DEL FORMULARIO
    =============================== */
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        let error = false;

        if (!servicio.value) { marcarInvalido(servicio); error = true; }
        if (!fecha.value)    { marcarInvalido(fecha); error = true; }
        if (!hora.value)     { marcarInvalido(hora); error = true; }

        const f = new Date(fecha.value + 'T00:00:00');
        if (f.getDay() === 0) { marcarInvalido(fecha); error = true; }

        if (error) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Revisa los campos marcados'
            });
            return;
        }

        /* CONFIRMACIÓN */
        const confirm = await Swal.fire({
            title: 'Confirmar cita',
            html: `
                <strong>Servicio:</strong> ${servicio.options[servicio.selectedIndex].text}<br>
                <strong>Fecha:</strong> ${fecha.value}<br>
                <strong>Hora:</strong> ${hora.value}
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, agendar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirm.isConfirmed) return;

        btnEnviar.disabled = true;
        const textoOriginal = btnEnviar.innerHTML;
        btnEnviar.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Guardando...`;

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    servicio: servicio.value,
                    fecha: fecha.value,
                    hora: hora.value,
                    notas: notas.value
                })
            });

            if (res.status === 422) {
                const json = await res.json();
                const msg = Object.values(json.errors).flat().join('<br>');
                errores.innerHTML = `<div class="alert alert-warning">${msg}</div>`;
                errores.style.display = 'block';
                throw new Error('Validación');
            }

            if (res.ok) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Cita agendada',
                    text: 'Tu cita se guardó correctamente',
                    timer: 1600,
                    showConfirmButton: false
                });
                window.location.reload();
            }

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo agendar la cita', 'error');
            btnEnviar.disabled = false;
            btnEnviar.innerHTML = textoOriginal;
        }
    });

});
</script>


@endsection
