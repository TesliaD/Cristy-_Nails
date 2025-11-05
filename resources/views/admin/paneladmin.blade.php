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
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('editarClienteModal');
  const form = document.getElementById('editarClienteForm');

  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');

    // 🔹 Cambiar la ruta del formulario dinámicamente
    form.action = "{{ url('admin/paneladmin/clientes') }}/" + id;
    // 🔹 Llenar los campos del modal
    document.getElementById('editUsuario').value = button.getAttribute('data-usuario');
    document.getElementById('editNombre').value = button.getAttribute('data-nombre');
    document.getElementById('editEmail').value = button.getAttribute('data-email');
    document.getElementById('editTelefono').value = button.getAttribute('data-telefono');
    document.getElementById('editDireccion').value = button.getAttribute('data-direccion');
    document.getElementById('editRol').value = button.getAttribute('data-rol');
  });
});
</script>


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

          <!-- Barra de búsqueda -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="buscarCliente" class="form-control" placeholder="Buscar cliente por nombre, email o teléfono...">
          </div>

          <!-- Botón para agregar cliente -->
          <a href="#" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevoClienteModal">
            <i class="bi bi-plus-circle"></i> Nuevo Cliente
          </a>

          <!-- Modal para nuevo cliente -->
          <div class="modal fade" id="nuevoClienteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('clientes.store') }}" method="POST">
                  @csrf
                  <div class="modal-header">
                    <h5 class="modal-title">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="form-label">Usuario</label>
                      <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Nombre</label>
                      <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Teléfono</label>
                      <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Dirección</label>
                      <input type="text" name="direccion" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Fecha de nacimiento</label>
                      <input type="date" name="fecha_nacimiento" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Rol</label>
                      <select name="rol" class="form-select">
                        <option value="cliente" selected>Cliente</option>
                        <option value="admin">Administrador</option>
                        <option value="empleado">Empleado</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Contraseña</label>
                      <input type="password" name="password" class="form-control" required>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Tabla de clientes -->
          <div class="table-responsive">
            <table class="table table-striped align-middle" id="tablaClientes">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Usuario</th>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th>Dirección</th>
                  <th>Rol</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($clientes as $cliente)
                <tr>
                  <td>{{ $cliente->id }}</td>
                  <td>{{ $cliente->usuario->usuario ?? '-' }}</td>
                  <td>{{ $cliente->nombre }}</td>
                  <td>{{ $cliente->usuario->email ?? '-' }}</td>
                  <td>{{ $cliente->telefono }}</td>
                  <td>{{ $cliente->direccion }}</td>
                  <td>{{ $cliente->usuario->rol ?? 'cliente' }}</td>
                  <td>
                    <!-- 🟦 Botón para abrir el modal de edición -->
                    <button type="button" class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#editarClienteModal"
                            data-id="{{ $cliente->id }}"
                            data-nombre="{{ $cliente->nombre }}"
                            data-usuario="{{ $cliente->usuario->usuario }}"
                            data-email="{{ $cliente->usuario->email }}"
                            data-telefono="{{ $cliente->telefono }}"
                            data-direccion="{{ $cliente->direccion }}"
                            data-fecha="{{ $cliente->fecha_nacimiento }}"
                            data-rol="{{ $cliente->usuario->rol }}">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar cliente?')">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
            <!-- ======================================= -->
            <!-- MODAL EDITAR CLIENTE -->
            <!-- ======================================= -->
            <div class="modal fade" id="editarClienteModal" tabindex="-1" aria-labelledby="editarClienteLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <form id="editarClienteForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                      <h5 class="modal-title" id="editarClienteLabel">Editar Cliente</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Usuario</label>
                          <input type="text" name="usuario" class="form-control" id="edit_usuario" required>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Nombre</label>
                          <input type="text" name="nombre" class="form-control" id="edit_nombre" required>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" class="form-control" id="edit_email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Teléfono</label>
                          <input type="text" name="telefono" class="form-control" id="edit_telefono">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Dirección</label>
                          <input type="text" name="direccion" class="form-control" id="edit_direccion">
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Fecha de nacimiento</label>
                          <input type="date" name="fecha_nacimiento" class="form-control" id="edit_fecha">
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select" id="edit_rol">
                          <option value="cliente">Cliente</option>
                          <option value="admin">Administrador</option>
                          <option value="empleado">Empleado</option>
                        </select>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn btn-success">Guardar cambios</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>  
      </Section>
      
      
            <!-- 🧩 SECCIÓN GESTIÓN DE SERVICIOS -->
      <section id="servicios" class="d-flex flex-column min-vh-100 py-4 px-4" style="background-color: #f8f9fa; display:none;">
        <div class="container-fluid flex-grow-1">

          <!-- 🌟 Encabezado -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h2 class="fw-bold text-primary mb-1">Gestión de Servicios</h2>
              <p class="text-muted">Administra los servicios ofrecidos por tu negocio.</p>
            </div>
            <button class="btn btn-success px-4 py-2 shadow-sm" onclick="document.getElementById('form-servicio').scrollIntoView({ behavior: 'smooth' })">
              ➕ Agregar Servicio
            </button>
          </div>

          <!-- 💅 FORMULARIO DE REGISTRO -->
          <div class="card shadow-sm border-0 mb-5" id="form-servicio">
            <div class="card-body">
              <form action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 align-items-end">
                  <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Nombre del servicio</label>
                    <input type="text" name="Nom_Servicio" class="form-control" placeholder="Ej. Uñas acrílicas" required>
                  </div>
                  <div class="col-lg-2 col-md-4">
                    <label class="form-label fw-semibold">Precio ($)</label>
                    <input type="number" step="0.01" name="Precio" class="form-control" required>
                  </div>
                  <div class="col-lg-2 col-md-4">
                    <label class="form-label fw-semibold">Duración (min)</label>
                    <input type="number" name="Duracion" class="form-control" required>
                  </div>
                  <div class="col-lg-5 col-md-8">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="Descripcion" class="form-control" rows="2" placeholder="Describe el servicio..."></textarea>
                  </div>
                  <div class="col-lg-5 col-md-6">
                    <label class="form-label fw-semibold">Imagen del servicio</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                  </div>
                  <div class="col-lg-3 col-md-6 mt-3">
                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm">
                      💅 Guardar Servicio
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- 🧾 TABLA DE SERVICIOS -->
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white fw-semibold">
              Lista de Servicios
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light text-center">
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
                    @forelse ($servicios as $servicio)
                      <tr>
                        <form action="{{ route('servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          @method('PUT')

                          <!-- Imagen -->
                          <td class="text-center" style="width: 120px;">
                            @if ($servicio->imagen)
                              <img src="{{ asset('storage/' . $servicio->imagen) }}" 
                                  alt="Imagen del servicio" 
                                  class="rounded shadow-sm mb-2" 
                                  width="80" height="80" style="object-fit:cover;">
                            @else
                              <span class="text-muted small">Sin imagen</span>
                            @endif
                            <input type="file" name="imagen" class="form-control form-control-sm mt-1" accept="image/*">
                          </td>

                          <!-- Nombre -->
                          <td style="width: 160px;">
                            <input type="text" name="Nom_Servicio" value="{{ $servicio->Nom_Servicio }}" class="form-control form-control-sm text-center">
                          </td>

                          <!-- Descripción -->
                          <td style="width: 250px;">
                            <textarea name="Descripcion" class="form-control form-control-sm" rows="2">{{ $servicio->Descripcion }}</textarea>
                          </td>

                          <!-- Precio -->
                          <td style="width: 100px;">
                            <input type="number" step="0.01" name="Precio" value="{{ $servicio->Precio }}" class="form-control form-control-sm text-center">
                          </td>

                          <!-- Duración -->
                          <td style="width: 90px;">
                            <input type="number" name="Duracion" value="{{ $servicio->Duracion }}" class="form-control form-control-sm text-center">
                          </td>

                          <!-- Activo -->
                          <td class="text-center" style="width: 60px;">
                            <input type="checkbox" name="Activo" value="1" {{ $servicio->Activo ? 'checked' : '' }}>
                          </td>

                          <!-- Acciones -->
                          <td class="text-center" style="width: 140px;">
                            <button type="submit" class="btn btn-sm btn-primary me-1" title="Guardar cambios">
                              💾
                            </button>
                        </form>
                        <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" title="Eliminar servicio" onclick="return confirm('¿Seguro que deseas eliminar este servicio?')">
                            🗑️
                          </button>
                        </form>
                          </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay servicios registrados aún.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Mensaje de éxito -->
          @if (session('success'))
            <div class="alert alert-success mt-4 shadow-sm">
              {{ session('success') }}
            </div>
          @endif

        </div>
      </section>


      <!-- CITAS -->
      <section id="citas" class="mb-5" style="display:none;">
        <h4 class="mb-3">Gestión de Citas</h4>
        <p>Aquí se pueden ver, crear o cancelar todas las citas de los clientes.</p>
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
