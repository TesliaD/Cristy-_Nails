<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Administrador</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="{{ asset('css/panelclientes.css') }}">
</head>

<body>
  <div class="d-flex" id="wrapper">

    <!-- Sidebar del Panel -->
    <div class="sidebar text-white p-3 sidebar">

      <a href="{{ url('/') }}">
          <img src="{{ asset('img/nailslogo.jpg') }}" 
              alt="Cristy Nails and Beauty"
              class="img-fluid d-block mx-auto"
              style="max-height: 80px;">
      </a>

      <ul class="nav flex-column mt-4">

        <!-- Dashboard / Inicio -->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('dashboard')">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>

        <!-- Gestión de clientes -->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('clientes')">
            <i class="bi bi-people-fill"></i> Clientes
          </a>
        </li>

        <!-- Gestión de citas -->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('citas')">
            <i class="bi bi-calendar-week"></i> Citas
          </a>
        </li>

        <!-- Gestión de servicios -->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-black fw-bold" onclick="mostrarSeccion('servicios')">
            <i class="bi bi-scissors"></i> Servicios
          </a>
        </li>

        <!-- Reportes -->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('reportes')">
            <i class="bi bi-bar-chart-line"></i> Reportes
          </a>
        </li>

        <!-- Configuración -->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('configuracion')">
            <i class="bi bi-gear-fill"></i> Configuración
          </a>
        </li>

        <!-- Cerrar sesión -->
        <li class="nav-item mt-4">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light w-100 fw-bold">
              <i class="bi bi-box-arrow-left"></i> Cerrar sesión
            </button>
          </form>
        </li>

      </ul>
    </div> <!--Fin del sidebar-->


    <!-- Contenido principal -->
    <div id="page-content" class="p-4 flex-grow-1">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-person-badge-fill"></i> Bienvenido, {{ Auth::user()->usuario ?? 'Administrador' }}</h1>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <!-- DASHBOARD -->
      <section id="dashboard" class="mb-5">
        <h4 class="mb-3">Dashboard</h4>
        <div class="row">
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
          <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
              <h5>Ingresos</h5>
              <p>${{ $totalIngresos ?? 0 }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- CLIENTES -->
      <section id="clientes" class="mb-5" style="display:none;">
        <h4 class="mb-3">Gestión de Clientes</h4>
        <a href="" class="btn btn-success mb-3">
          <i class="bi bi-plus-circle"></i> Nuevo Cliente
        </a>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
             
              <tr>
             
                <td>
                  <a href="" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <form action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- CITAS -->
      <section id="citas" class="mb-5" style="display:none;">
        <h4 class="mb-3">Gestión de Citas</h4>
        <p>Aquí se pueden ver, crear o cancelar todas las citas de los clientes.</p>
      </section>


    <!-- SERVICIOS -->
    <section id="servicios" class="mb-5" style="display:none;">
        <h4 class="mb-3">Gestión de Servicios</h4>
        <p>Agregar, editar o eliminar los servicios disponibles.</p>

      <!-- Formulario para agregar servicio -->
        <form action="{{ route('servicios.store') }}" method="POST" class="mb-4">
          @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <input type="text" name="Nom_Servicio" class="form-control" placeholder="Nombre del servicio" required>
        </div>
        <div class="col-md-3">
          <input type="number" step="0.01" name="Precio" class="form-control" placeholder="Precio" required>
        </div>
        <div class="col-md-3">
          <input type="number" name="Duracion" class="form-control" placeholder="Duración (min)" required>
        </div>
        <div class="col-md-8 mt-2">
          <textarea name="Descripcion" class="form-control" placeholder="Descripción" rows="2"></textarea>
        </div>
        <div class="col-md-8 mt-2">
          <input type="file" name="imagen" class="form-control">
        </div>
        <div class="col-md-4 mt-2">
          <button type="submit" class="btn btn-success w-100">Agregar Servicio</button>
        </div>
      </div>
    </form>

    <!-- Tabla de servicios -->
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>Imagen</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Duración</th>
          <th>Activo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($servicios as $servicio)
          <tr>
            <form action="{{ route('servicios.update', $servicio->id) }}" method="POST">
              @csrf
              @method('PUT')
              <td><input type="file" name="imagen" class="form-control" accept="image/*"></td>
              <td><input type="text" name="Nom_Servicio" value="{{ $servicio->Nom_Servicio }}" class="form-control"></td>
              <td><textarea name="Descripcion" class="form-control">{{ $servicio->Descripcion }}</textarea></td>
              <td><input type="number" step="0.01" name="precio" value="{{ $servicio->Precio }}" class="form-control"></td>
              <td><input type="number" name="Duracion" value="{{ $servicio->Duracion }}" class="form-control"></td>
              <td class="text-center">
                <input type="checkbox" name="activo" value="1" {{ $servicio->Activo ? 'checked' : '' }}>
              </td>
              <td class="text-center">
                <button type="submit" class="btn btn-primary btn-sm">💾</button>
            </form>
            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
            </form>
              </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    @if (session('success'))
      <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
  </section>


      <!-- REPORTES -->
      <section id="reportes" class="mb-5" style="display:none;">
        <h4 class="mb-3">Reportes y Estadísticas</h4>
        <p>Generar reportes de clientes, citas, servicios e ingresos.</p>
      </section>

      <!-- CONFIGURACIÓN -->
      <section id="configuracion" class="mb-5" style="display:none;">
        <h4 class="mb-3">Configuración del Sistema</h4>
        <p>Opciones de personalización del sistema y gestión de roles.</p>
      </section>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function mostrarSeccion(id) {
      document.querySelectorAll('section').forEach(sec => sec.style.display = 'none');
      document.getElementById(id).style.display = 'block';
    }
  </script>
</body>
</html>
