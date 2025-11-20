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

        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('registrar')">
            <i class="bi bi-journal-check"></i> Registrar Datos
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
        <div class="alert alert-success">{{ session('success') }}</div>
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
        <form method="POST" action="{{ route('panelcliente.update') }}">
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

        <div id="calendar"></div>
      </section>

      <!-- Registrar datos -->
      <section id="registrar" class="mb-5" style="display:none;">
        <h4 class="mb-3">Registrar Datos</h4>
        <button class="btn btn-success"><i class="bi bi-plus-circle"></i> Registrar información</button>
      </section>

    </div>
  </div>

  <!-- 🎯 MODAL DETALLE DE CITA -->
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
            <button type="submit" class="btn btn-danger">Cancelar cita</button>
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
              cancelarUrl: '{{ route("citas.cancelar", $cita->id) }}'
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

          document.getElementById("formCancelarCita").action =
            info.event.extendedProps.cancelarUrl;

          let modal = new bootstrap.Modal(document.getElementById('modalCita'));
          modal.show();
        }
      });

      calendar.render();
    }
  </script>

</body>
</html>
