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

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="{{ asset('css/panelclientes.css') }}">
</head>

<body>
  <div class="d-flex" id="wrapper">
    

    <!-- Sidebar del Panel -->
    <div class="sidebar text-white p-3 sidebar">

      <!--La imagen regresa a al landing page principal-->
      <a href="{{ url('/') }}">
          <img src="{{ asset('img/nailslogo.jpg') }}" 
              alt="Cristy Nails and Beauty"
              class="img-fluid d-block mx-auto"
              style="max-height: 80px;">
      </a>

      <!--Barra de navegación-->

      <ul class="nav flex-column mt-4">
    
        <!--Primer boton redirige a perfil-->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('perfil')">
            <i class="bi bi-person-circle"></i> Mi Perfil
          </a>
        </li>

        <!--Boton para actualizar el perfil del usuario-->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('actualizar')">
            <i class="bi bi-pencil-square"></i> Actualizar Perfil
          </a>
        </li>

        <!--Boton para ver las citas que tiene el cliente-->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('citas')">
            <i class="bi bi-calendar-week"></i> Mis Citas
          </a>
        </li>

        <!--Boton para registrar datos-->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('registrar')">
            <i class="bi bi-journal-check"></i> Registrar Datos
          </a>
        </li>

        <!--Boton para la configuracion del perfil-->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('configuracion')">
            <i class="bi bi-gear-fill"></i> Configuración
          </a>
        </li>

        <!--Boton para Cerrar la sesion del usuario-->
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

    <!-- Contenido principal -->
    <div id="page-content" class="p-4 flex-grow-1">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-person-fill-check"></i> Bienvenido, {{ Auth::user()->nombre ?? 'Cliente' }}</h1>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <!-- PERFIL: Solo muestra la información -->
      <section id="perfil" class="mb-5">
        <h4 class="mb-3">Mi Perfil</h4>
        <div class="card p-4 shadow-sm">
          <p><strong>Nombre:</strong> {{ $user->nombre ?? 'No registrado' }}</p>
          <p><strong>Email:</strong> {{ $user->email ?? 'No registrado' }}</p>
          <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'No registrado' }}</p>
          <p><strong>Dirección:</strong> {{ $cliente->direccion ?? 'No registrada' }}</p>
          <p><strong>Fecha de nacimiento:</strong> 
            {{ isset($cliente->fecha_nacimiento) ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}
          </p>
        </div>
      </section>

      <!-- ACTUALIZAR PERFIL: Sección oculta al inicio -->
      <section id="actualizar" class="mb-5" style="display:none;">
        <h4 class="mb-3">Actualizar mi perfil</h4>
        <form method="POST" action="{{ route('panelcliente.update') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $user->nombre ?? '') }}">
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

      <!-- Citas -->
      <section id="citas" class="mb-5" style="display:none;">
        <h4 class="mb-3">Mis Citas</h4>
        <div class="alert alert-info"><i class="bi bi-calendar2-week"></i> Aún no tienes citas registradas.</div>
      </section>

      <!-- Registrar datos -->
      <section id="registrar" class="mb-5" style="display:none;">
        <h4 class="mb-3">Registrar Datos</h4>
        <button class="btn btn-success"><i class="bi bi-plus-circle"></i> Registrar información</button>
      </section>

      <!-- Configuración -->
      <section id="configuracion" style="display:none;">
        <h4 class="mb-3">Configuración</h4>
        <p>Opciones de personalización próximamente.</p>
      </section>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para alternar secciones -->
  <script>
    function mostrarSeccion(id) {
      document.querySelectorAll('section').forEach(sec => sec.style.display = 'none');
      document.getElementById(id).style.display = 'block';
    }
  </script>
</body>
</html>
