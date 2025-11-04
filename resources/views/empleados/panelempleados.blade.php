<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Empleado</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background: #f7f7f7; }
    .sidebar { background-color: #8b008b; min-height: 100vh; width: 230px; }
    .sidebar a { color: white; text-decoration: none; }
    .sidebar a:hover { background: rgba(255,255,255,0.2); border-radius: 5px; }
  </style>
</head>

<body>
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
        <a href="#" class="nav-link fw-bold" onclick="mostrarSeccion('servicios')">
          <i class="bi bi-scissors"></i> Servicios
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
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
    
        </tbody>
      </table>
    </section>

    <!-- SERVICIOS -->
    <section id="servicios" style="display:none;">
      <h4><i class="bi bi-scissors"></i> Servicios Disponibles</h4>
      <div class="row">

      </div>
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
        <tbody>
        
        </tbody>
      </table>
    </section>
  </div>
</div>

<script>
function mostrarSeccion(id) {
  document.querySelectorAll('section').forEach(sec => sec.style.display = 'none');
  document.getElementById(id).style.display = 'block';
}
</script>
</body>
</html>
