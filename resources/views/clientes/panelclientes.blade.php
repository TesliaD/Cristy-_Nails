<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel del Cliente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- FullCalendar -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/panelclientes.css') }}">

  <<!--Script de las alertas-->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="sidebar text-white p-3 sidebar">
      <a href="{{ route('dashboard') }}">
          <img src="{{ asset('img/nailslogo.jpg') }}" 
              alt="Cristy Nails and Beauty"
              class="img-fluid d-block mx-auto"
              style="max-height: 80px;">
      </a>

      <ul class="nav flex-column mt-4">

        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('perfil')">
            <i class="bi bi-person-circle"></i> Mi Perfil
          </a>
        </li>

        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('actualizar')">
            <i class="bi bi-pencil-square"></i> Actualizar Perfil
          </a>
        </li>

        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('citas')">
            <i class="bi bi-calendar-week"></i> Mis Citas
          </a>
        </li>

        <li class="nav-item mt-4">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light w-100 fw-bold">
              <i class="bi bi-box-arrow-left"></i> Cerrar sesión
            </button>
          </form>
        </li>
      </ul>
    </div>

    <!-- Contenido -->
    <div id="page-content" class="p-4 flex-grow-1">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-person-fill-check"></i> Bienvenido, {{ Auth::user()->usuario ?? 'Cliente' }}</h1>
      </div>

      @if(session('success'))
      <script>
      Swal.fire({
          icon: "success",
          title: "¡Éxito!",
          text: "{{ session('success') }}",
          confirmButtonColor: "#d63384"
      });
      </script>
      @endif

      @if(session('error'))
      <script>
      Swal.fire({
          icon: "error",
          title: "Error",
          text: "{{ session('error') }}",
          confirmButtonColor: "#d63384"
      });
      </script>
      @endif


      <!-- PERFIL -->
      <section id="perfil" class="mb-5">
        <h4 class="mb-3">Mi Perfil</h4>
        <div class="card p-4 shadow-sm">
          <p><strong>Nombre:</strong> {{ $cliente->nombre ?? 'No registrado' }}</p>
          <p><strong>Email:</strong> {{ $user->email ?? 'No registrado' }}</p>
          <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'No registrado' }}</p>
          <p><strong>Dirección:</strong> {{ $cliente->direccion ?? 'No registrada' }}</p>
          <p><strong>Fecha de nacimiento:</strong> 
            {{ isset($cliente->fecha_nacimiento) ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}
          </p>
        </div>
      </section>

      <!-- ACTUALIZAR PERFIL -->
      <section id="actualizar" class="mb-5" style="display:none;">
        <h4 class="mb-3">Actualizar mi perfil</h4>
        <form id="formPerfil" method="POST" action="{{ route('panelcliente.update') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre ?? '') }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono ?? '') }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Dirección</label>
            <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $cliente->direccion ?? '') }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control"
              value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ?? '') }}">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nueva contraseña</label>
              <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Confirmar contraseña</label>
              <input type="password" name="password_confirmation" class="form-control">
            </div>
          </div>

          <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary px-4 py-2">Guardar cambios</button>
          </div>
        </form>
      </section>

      <!-- CITAS -->
      <section id="citas" class="mb-5" style="display:none;">
        <h4 class="mb-3">Mis Citas</h4>

        <div id="alertaCitas" class="mini-alert center-alert">
        ¡RECUERDA QUE DEBES DE TENER 2 DÍAS DE ANTICIPACIÓN PARA PODER CANCELAR UNA CITA!
        </div>

        <div id="calendar"></div>
      </section>

    </div>
  </div>

  <!--MODAL DETALLE DE CITA -->
  <div class="modal fade" id="modalCita" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Detalles de la Cita</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <p><strong>Servicio:</strong> <span id="modalServicio"></span></p>
          <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
          <p><strong>Hora:</strong> <span id="modalHora"></span></p>
          <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
        </div>

        <div class="modal-footer">
          <form id="formCancelarCita" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger" onclick="confirmarCancelacion()">Cancelar cita</button>
          </form>

          <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>

      </div>
    </div>


  </div>

  <!-- JS GENERAL -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

  <script>
    let calendar = null;

    function mostrarSeccion(id) {
      document.querySelectorAll('section').forEach(sec => sec.style.display = 'none');
      document.getElementById(id).style.display = 'block';

      if (id === "citas") {
        mostrarAlertaCitas();

        setTimeout(() => {
            if (!calendar) {
                iniciarCalendario();
            } else {
                calendar.render();
            }
        }, 100);
      }
    }

    function iniciarCalendario() {
      const calendarEl = document.getElementById('calendar');

      calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: "auto",

        events: [
          @foreach ($citas as $cita)
          {
            id: '{{ $cita->id }}',
            title: '{{ $cita->servicio->nombre }}',
            start: '{{ $cita->fecha }}T{{ $cita->hora }}',
            extendedProps: {
              servicio: '{{ $cita->servicio->nombre }}',
              fecha: '{{ $cita->fecha }}',
              hora: '{{ $cita->hora }}',
              estado: '{{ $cita->estado ?? "Pendiente" }}',
              cancelarUrl: '{{ route("citas.cancelar", $cita->id) }}',
              isPast: '{{ \Carbon\Carbon::parse($cita->fecha . " " . $cita->hora)->isPast() ? "1" : "0" }}',
              bloqueadoPorFecha: '{{ \Carbon\Carbon::parse($cita->fecha)->subDays(2)->startOfDay()->lessThan(now()->startOfDay()) ? "1" : "0" }}',
              estadoCancelado: '{{ $cita->estado === "cancelada" ? "1" : "0" }}',
          },

            color: '#ff79bc'
          },
          @endforeach
        ],

        eventClick: function(info) {
          document.getElementById("modalServicio").innerText = info.event.extendedProps.servicio;
          document.getElementById("modalFecha").innerText = info.event.extendedProps.fecha;
          document.getElementById("modalHora").innerText = info.event.extendedProps.hora;
          document.getElementById("modalEstado").innerText = info.event.extendedProps.estado;

          const btnCancelar = document.querySelector("#formCancelarCita button.btn-danger");

          // Si la cita es del pasado → deshabilita cancelar
          if (
            info.event.extendedProps.isPast === "1" ||
            info.event.extendedProps.estadoCancelado === "1" ||
            info.event.extendedProps.bloqueadoPorFecha === "1"
        ) {
            btnCancelar.disabled = true;
            btnCancelar.innerText = "No disponible";
        } else {
            btnCancelar.disabled = false;
            btnCancelar.innerText = "Cancelar cita";
        }

          document.getElementById("formCancelarCita").action =
            info.event.extendedProps.cancelarUrl;

          let modal = new bootstrap.Modal(document.getElementById('modalCita'));
          modal.show();
        }

      });

      calendar.render();
    }
  </script>

  
  <script>
    // Mostrar alerta
    function mostrarAlertaCitas() {
        const alerta = document.getElementById("alertaCitas");
        if (!alerta) return;

        alerta.classList.add("show");

        setTimeout(() => {
            alerta.classList.remove("show");
        }, 5000); // La alerta dura 5 segundos
    }
    </script>

    <!--Alerta de las validaciones-->
    <script>
    document.addEventListener("DOMContentLoaded", () => {

        /* ------------------------- VALIDACIONES ------------------------- */

        const formUpdate = document.querySelector('form[action="{{ route("panelcliente.update") }}"]');

        if (formUpdate) {
            formUpdate.addEventListener("submit", function(e) {
                e.preventDefault(); // Evitar envío hasta validar

                let nombre = formUpdate.nombre.value.trim();
                let email = formUpdate.email.value.trim();
                let telefono = formUpdate.telefono.value.trim();
                let pass = formUpdate.password.value.trim();
                let pass2 = formUpdate.password_confirmation.value.trim();

                // Validación Nombre (solo letras)
                if (!/^[A-Za-zÁÉÍÓÚÑáéíóúñ ]+$/.test(nombre) || nombre.length < 3) {
                    return Swal.fire({
                        icon: "error",
                        title: "Nombre inválido",
                        text: "El nombre solo debe contener letras y tener al menos 3 caracteres."
                    });
                }

                // Validación Email
                let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regexEmail.test(email)) {
                    return Swal.fire({
                        icon: "error",
                        title: "Correo inválido",
                        text: "Ingresa un correo electrónico válido."
                    });
                }

                // Validación Teléfono (solo números)
                if (!/^[0-9]+$/.test(telefono) || telefono.length < 10) {
                    return Swal.fire({
                        icon: "error",
                        title: "Teléfono inválido",
                        text: "El teléfono debe contener solo números y al menos 10 dígitos."
                    });
                }

                // Validación Contraseñas
                if (pass.length > 0 || pass2.length > 0) {
                    if (pass.length < 6) {
                        return Swal.fire({
                            icon: "error",
                            title: "Contraseña muy corta",
                            text: "La contraseña debe tener al menos 6 caracteres."
                        });
                    }

                    if (pass !== pass2) {
                        return Swal.fire({
                            icon: "error",
                            title: "Contraseñas no coinciden",
                            text: "Debes escribir la misma contraseña en ambos campos."
                        });
                    }
                }

                /* ----------------- SI PASA TODO, CONFIRMA ENVÍO ----------------- */
                Swal.fire({
                    title: "Guardar cambios",
                    text: "¿Deseas actualizar tus datos?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Sí, actualizar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        formUpdate.submit(); // Enviar formulario
                    }
                });

            });
        }

        /* ------------------------- ALERTA DESPUÉS DE ACTUALIZAR ------------------------- */

        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "¡Actualizado!",
                text: "{{ session('success') }}",
                confirmButtonText: "Ok"
            });
        @endif

    });
    </script>

    <!--Alerta para cancelar una cita-->
    <script>
    function confirmarCancelacion() {
        Swal.fire({
            title: "¿Cancelar cita?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, cancelar",
            cancelButtonText: "No, volver",
            confirmButtonColor: "#d63384"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formCancelarCita').submit();
            }
        });
    }
    </script>

    <!--Alertas para actualizar el perfil-->
    <script>
    document.getElementById("formPerfil").addEventListener("submit", function(e) {

        let email = document.querySelector("input[name='email']").value;
        let nombre = document.querySelector("input[name='nombre']").value;

        // Validaciones básicas
        if(nombre.trim().length < 3){
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Nombre demasiado corto",
                text: "Debes escribir al menos 3 caracteres."
            });
            return;
        }

        if(!email.includes("@")){
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Email inválido",
                text: "Ingresa un email válido."
            });
            return;
        }

        e.preventDefault(); // detener envío

        Swal.fire({
            title: "¿Guardar cambios?",
            text: "Se actualizará tu información.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#0d6efd"
        }).then(result => {
            if(result.isConfirmed){
                document.getElementById("formPerfil").submit();
            }
        });

    });
    </script>


    
</body>
</html>
