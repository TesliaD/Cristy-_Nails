<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Empleado</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/panelempleados.css') }}">

  
  <style>
    body { background: #f7f7f7; }
    .sidebar { background-color: #8b008b; min-height: 100vh; width: 230px; }
    .sidebar a { color: white; text-decoration: none; }
    .sidebar a:hover { background: rgba(255,255,255,0.2); border-radius: 5px; }
  </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="{{ asset('js/calendario.js') }}"></script>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar p-3">
    <div class="text-center mb-4">
      <img src="{{ asset('img/nailslogo.jpg') }}" alt="Logo" class="img-fluid rounded-circle" style="max-width: 100px;">
      <h5 class="mt-2">Empleado</h5>
    </div>

    <ul class="nav flex-column">
      <li class="nav-item mb-2">
        <a href="#" class="nav-link fw-bold" onclick="mostrarSeccion('inicio')">
          <i class="bi bi-house-door"></i> Inicio
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="#" class="nav-link fw-bold" onclick="mostrarSeccion('citas')">
          <i class="bi bi-calendar-week"></i> Mis Citas
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="#" class="nav-link fw-bold" onclick="mostrarSeccion('clientes')">
          <i class="bi bi-person-lines-fill"></i> Clientes
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
  <div class="flex-grow-1 p-4">
    <h3 class="mb-4">
      <i class="bi bi-person-workspace"></i> Bienvenido, {{ Auth::user()->usuario ?? 'empleado' }}
    </h3>

    <!-- INICIO -->
    <section id="inicio">
      <div class="alert alert-info">
        <strong>¡Buen día!</strong> Aquí puedes consultar tus citas, clientes y servicios del día.
      </div>
    </section>

    <!-- DASHBOARD -->
    
      <section id="dashboard" class="mb-5">
        <div class="d-flex justify-content-center flex-wrap gap-3">
          <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
              <h5>Clientes</h5>
              <p>{{ $totalClientes ?? 0 }}</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
              <h5>Citas</h5>
              <p>{{ $totalCitas ?? 0 }}</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
              <h5>Servicios</h5>
              <p>{{ $totalServicios ?? 0 }}</p>
            </div>
          </div>
        </div> 
      </section>

    <!-- CALENDARIO -->
    <section id="calendario">
      <div class="calendariojoto">
        <header class="clearfix">
        </header>	
        <section>
        <div class="main">
                  <div class="custom-calendar-wrap">
                      <div id="custom-inner" class="custom-inner">
                          <div class="custom-header clearfix">
                              <nav>
                                  <span id="custom-prev" class="custom-prev"></span>
                                  <span id="custom-next" class="custom-next"></span>
                              </nav>
                              <h2 id="custom-month" class="custom-month"></h2>
                              <h3 id="custom-year" class="custom-year"></h3>
                          </div>
                          <div id="calendar" class="fc-calendar-container"></div>
                      </div>
                  </div>
              </div>
      </div>

    <div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEventoLabel">Detalles del día</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body" id="contenidoModal">
          </div>
        </div>
      </div>
    </div>

    </section>


    <!-- CITAS -->
    <section id="citas" style="display:none;">
      <h4><i class="bi bi-calendar-week"></i> Mis Citas de Hoy</h4>
      <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
          <tr>
            <th>Cliente</th>
            <th>Servicio</th>
            <th>Hora</th>
            <th>Estado</th>
          </tr>
        </thead>
      <tbody>
        @forelse($citas as $cita)
          <tr>
            <td>{{ $cita->cliente->nombre ?? 'Sin cliente' }}</td>
            <td>{{ $cita->servicio->Nom_Servicio ?? 'Sin servicio' }}</td>
            <td>{{ $cita->hora }}</td>
            <td>{{ $cita->estado ?? 'Pendiente' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">No tienes citas</td>
          </tr>
        @endforelse
      </tbody>

      </table>
    </section>

    <!-- CLIENTES -->
    <section id="clientes" style="display:none;">
      <h4><i class="bi bi-people-fill"></i> Mis Clientes</h4>
      <table class="table table-striped mt-3">
        <thead class="table-dark">
          <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
          </tr>
        </thead>
        @php
        $usuariosMostrados = [];
        @endphp

        <tbody>
          @foreach($citas as $cita)
              @php
                  $email = $cita->cliente->usuario->email ?? null;
              @endphp

              @if($email && !in_array($email, $usuariosMostrados))
                  <tr>
                      <td>{{ $cita->cliente->nombre }}</td>
                      <td>{{ $cita->cliente->telefono }}</td>
                      <td>{{ $email }}</td>
                  </tr>

                  @php
                      $usuariosMostrados[] = $email;
                  @endphp
              @endif
          @endforeach
        </tbody>

      </table>
    </section>
  </div>
</div>

<script>

function mostrarSeccion(id) {
  const secciones = ['inicio', 'dashboard', 'citas', 'servicios', 'clientes', 'calendario'];
  
  secciones.forEach(sec => {
    const elemento = document.getElementById(sec);
    if (elemento) elemento.style.display = 'none';
  });

  if (id === 'inicio') {
    document.getElementById('inicio').style.display = 'block';
    document.getElementById('dashboard').style.display = 'block';
    document.getElementById('calendario').style.display = 'block';
  } else {
    const seccionActual = document.getElementById(id);
    if (seccionActual) seccionActual.style.display = 'block';
  }
}

</script>
</body>
</html>
