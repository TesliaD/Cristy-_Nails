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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="{{ asset('css/panelclientes.css') }}">
  <link rel="stylesheet" href="{{ asset('css/tarjetasdereporte.css') }}">
</head>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('editarClienteModal');
  const form = document.getElementById('editarClienteForm');

  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');

    // Cambiar la ruta del formulario dinámicamente
    form.action = "{{ url('admin/paneladmin/clientes') }}/" + id;
    // Llenar los campos del modal
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

      <a href="{{ route('dashboard') }}">
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

        <!--Gestion de Empleados-->
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white fw-bold" onclick="mostrarSeccion('empleados')">
              <i class="bi bi-person-raised-hand"></i> Empleados
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
              <p>${{ number_format($totalIngresos ?? 0, 2) }}</p>
            </div>
          </div>
        </div>
      </section>
      
      <!-- EMPLEADOS -->
      <section id="empleados" class="mb-5" style="display:none;">
          <h4 class="mb-3">Gestión de Empleados</h4>

          <!-- Barra búsqueda -->
          <div class="input-group mb-3">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input type="text" id="buscarEmpleado" class="form-control" placeholder="Buscar empleado...">
          </div>

          <!-- Botón agregar -->
          <a href="#" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevoEmpleadoModal">
              <i class="bi bi-plus-circle"></i> Nuevo Empleado
          </a>

          <div class="table-responsive">
              <table class="table table-striped align-middle" id="tablaEmpleados">
                  <thead>
                  <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Email</th>
                      <th>Rol</th>
                      <th>Acciones</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($empleados as $empleado)
                      <tr>
                          <td>{{ $empleado->id }}</td>
                          <td>{{ $empleado->usuario }}</td>
                          <td>{{ $empleado->email }}</td>
                          <td>{{ $empleado->rol }}</td>

                          <td>

                              <!-- Botón editar -->
                              <button class="btn btn-primary btn-sm editarEmpleadoBtn"
                                      data-id="{{ $empleado->id }}"
                                      data-usuario="{{ $empleado->usuario }}"
                                      data-email="{{ $empleado->email }}"
                                      data-rol="{{ $empleado->rol }}">
                                  <i class="bi bi-pencil-square"></i>
                              </button>

                              <!-- Botón eliminar -->
                              <form action="{{ route('empleados.destroy', $empleado->id) }}"
                                    method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')

                                  <button onclick="return confirm('¿Eliminar empleado?')" class="btn btn-danger btn-sm">
                                      <i class="bi bi-trash"></i>
                                  </button>
                              </form>

                          </td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
          </div>
      </section>

      <!-- MODAL: NUEVO EMPLEADO -->
      <div class="modal fade" id="nuevoEmpleadoModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <form action="{{ route('empleados.store') }}" method="POST">
                      @csrf

                      <div class="modal-header">
                          <h5 class="modal-title">Agregar Empleado</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">

                          <div class="mb-3">
                              <label class="form-label">Usuario</label>
                              <input name="usuario" class="form-control" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input type="email" name="email" class="form-control" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Contraseña</label>
                              <input type="password" name="password" class="form-control" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Rol</label>
                              <select name="rol" class="form-select" required>
                                  <option value="empleado">Empleado</option>
                                  <option value="admin">Administrador</option>
                              </select>
                          </div>

                      </div>

                      <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button class="btn btn-success" type="submit">Guardar</button>
                      </div>

                  </form>
              </div>
          </div>
      </div>

      <!-- MODAL: EDITAR EMPLEADO -->
      <div class="modal fade" id="editarEmpleadoModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">

                  <form id="editarEmpleadoForm" method="POST">
                      @csrf
                      @method('PUT')

                      <div class="modal-header">
                          <h5 class="modal-title">Editar Empleado</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">

                          <div class="mb-3">
                              <label class="form-label">Usuario</label>
                              <input name="usuario" id="edit_emp_usuario" class="form-control" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input type="email" name="email" id="edit_emp_email" class="form-control" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Rol</label>
                              <select name="rol" id="edit_emp_rol" class="form-select">
                                  <option value="empleado">Empleado</option>
                                  <option value="admin">Administrador</option>
                              </select>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Nueva contraseña (opcional)</label>
                              <input type="password" name="password" class="form-control"
                                    placeholder="Dejar vacío para no cambiar">
                          </div>

                      </div>

                      <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-success">Guardar cambios</button>
                      </div>

                  </form>

              </div>
          </div>
      </div>

      <!-- SCRIPT MODAL EDICIÓN -->
      <script>
      document.addEventListener('DOMContentLoaded', () => {

          let modal = new bootstrap.Modal(document.getElementById('editarEmpleadoModal'));
          let form = document.getElementById('editarEmpleadoForm');

          document.querySelectorAll('.editarEmpleadoBtn').forEach(btn => {
              btn.addEventListener('click', () => {

                  form.action = `/empleados/${btn.dataset.id}`;

                  document.getElementById('edit_emp_usuario').value = btn.dataset.usuario;
                  document.getElementById('edit_emp_email').value = btn.dataset.email;
                  document.getElementById('edit_emp_rol').value = btn.dataset.rol;

                  modal.show();
              });
          });

      });
      </script>



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
                    <label class="form-label">Usuario</label>
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
                <td>{{ $cliente->usuario->usuario ?? 'SIN USUARIO' }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->usuario->email ?? 'N/A' }}</td>
                <td>{{ $cliente->telefono }}</td>
                <td>{{ $cliente->direccion }}</td>
                <td>{{ $cliente->usuario->rol ?? 'cliente' }}</td>

                <td>
                  <!-- EDITAR -->
                  <button type="button" class="btn btn-sm btn-primary editarClienteBtn"
                          data-id="{{ $cliente->id }}"
                          data-nombre="{{ $cliente->nombre }}"
                          data-usuario="{{ $cliente->usuario->usuario ?? '' }}"
                          data-email="{{ $cliente->usuario->email ?? '' }}"
                          data-telefono="{{ $cliente->telefono }}"
                          data-direccion="{{ $cliente->direccion }}"
                          data-fecha="{{ $cliente->fecha_nacimiento }}"
                          data-rol="{{ $cliente->usuario->rol }}">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <!-- ELIMINAR -->
                  <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar cliente?')">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Modal Editar Cliente -->
        <div class="modal fade" id="editarClienteModal" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <form id="editarClienteForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                  <h5 class="modal-title">Editar Cliente</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                      <label class="form-label">Fecha nacimiento</label>
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
      </section>


      <!-- SCRIPT EDITAR CLIENTE -->
      <script>
      document.addEventListener('DOMContentLoaded', () => {
        const modal = new bootstrap.Modal(document.getElementById('editarClienteModal'));
        const form = document.getElementById('editarClienteForm');

        document.querySelectorAll('.editarClienteBtn').forEach(btn => {
          btn.addEventListener('click', () => {
            form.action = `/clientes/${btn.dataset.id}`;
            document.getElementById('edit_usuario').value = btn.dataset.usuario;
            document.getElementById('edit_nombre').value = btn.dataset.nombre;
            document.getElementById('edit_email').value = btn.dataset.email;
            document.getElementById('edit_telefono').value = btn.dataset.telefono;
            document.getElementById('edit_direccion').value = btn.dataset.direccion;
            document.getElementById('edit_fecha').value = btn.dataset.fecha;
            document.getElementById('edit_rol').value = btn.dataset.rol;
            modal.show();
          });
        });
      });
      </script>

      <!-- SCRIPT BUSCAR CLIENTES -->
      <script>
      document.getElementById('buscarCliente').addEventListener('keyup', function () {
        let filtro = this.value.toLowerCase();
        let filas = document.querySelectorAll('#tablaClientes tbody tr');

        filas.forEach(fila => {
          let texto = fila.innerText.toLowerCase();
          fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
      });
      </script>


       <!-- SERVICIOS -->
      <section id="servicios" class="mb-5" style="display:none; min-height:100vh;">
        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h2 class="fw-bold text-primary mb-1">Gestión de Servicios</h2>
              <p class="text-muted">Administra los servicios ofrecidos por tu negocio.</p>
            </div>
            <button class="btn btn-success px-4 py-2 shadow-sm" onclick="document.getElementById('form-servicio').scrollIntoView({ behavior: 'smooth' })">
              ➕ Agregar Servicio
            </button>
          </div>

          <!-- FORMULARIO -->
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

          <!-- TABLA -->
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
                          <td class="text-center">
                            @if ($servicio->imagen)
                              <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="" class="rounded shadow-sm mb-2" width="80" height="80" style="object-fit:cover;">
                            @else
                              <span class="text-muted small">Sin imagen</span>
                            @endif
                            <input type="file" name="imagen" class="form-control form-control-sm mt-1" accept="image/*">
                          </td>
                          <td><input type="text" name="Nom_Servicio" value="{{ $servicio->Nom_Servicio }}" class="form-control form-control-sm text-center"></td>
                          <td><textarea name="Descripcion" class="form-control form-control-sm" rows="2">{{ $servicio->Descripcion }}</textarea></td>
                          <td><input type="number" step="0.01" name="Precio" value="{{ $servicio->Precio }}" class="form-control form-control-sm text-center"></td>
                          <td><input type="number" name="Duracion" value="{{ $servicio->Duracion }}" class="form-control form-control-sm text-center"></td>
                          <td class="text-center"><input type="checkbox" name="Activo" value="1" {{ $servicio->Activo ? 'checked' : '' }}></td>
                          <td class="text-center">
                            <button type="submit" class="btn btn-sm btn-primary me-1">💾</button>
                        </form>
                        <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline;">
                          @csrf @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este servicio?')">🗑️</button>
                        </form>
                          </td>
                      </tr>
                    @empty
                      <tr><td colspan="7" class="text-center text-muted py-4">No hay servicios registrados.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>      

            <!-- ============================= -->
      <!-- CITAS (CALENDARIO) -->
      <!-- ============================= -->
      <section id="citas" style="display:none; min-height:100vh;">
        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h2 class="fw-bold text-primary mb-1">Citas que Atender</h2>
              <p class="text-muted">Agenda y gestiona las citas de los clientes.</p>
            </div>
          </div>

          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div id="calendar"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============================= -->
      <!-- MODAL PARA CITA (con notas) -->
      <!-- ============================= -->
      <div class="modal fade" id="citaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form id="formCita">
              <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Agregar Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <input type="hidden" id="cita_id" name="cita_id">

                <div class="mb-3">
                  <label class="form-label">Fecha</label>
                  <input type="date" name="fecha" id="fechaCita" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Hora</label>
                  <input type="time" name="hora" id="horaCita" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Cliente</label>
                  <select name="cliente_id" id="clienteCita" class="form-select" required>
                    <option value="">Selecciona un cliente</option>
                    @foreach($clientes as $c)
                      <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Servicio</label>
                  <select name="servicio_id" id="servicioCita" class="form-select" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach($servicios as $s)
                      <option value="{{ $s->id }}">{{ $s->Nom_Servicio }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Empleado</label>
                  <select name="empleado_id" id="empleadoCita" class="form-select" required>
                    <option value="">Selecciona un empleado</option>
                    @foreach($empleados as $e)
                      <option value="{{ $e->id }}">{{ $e->usuario }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- 📝 Campo de notas -->
                <div class="mb-3">
                  <label class="form-label">Notas</label>
                  <textarea name="notas" id="notasCita" class="form-control" rows="3" placeholder="Detalles adicionales sobre la cita..."></textarea>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminar" style="display:none;">Eliminar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

            
  <!-- REPORTES -->
  <section id="reportes" class="mb-5" style="display:none;">
      <h4 class="mb-3">Reportes y Estadísticas</h4>
      <p>Generar reportes de clientes, citas, servicios e ingresos.</p>

      <div class="contenedor-tarjetas">

          <a class="tarjetareportes card-btn" data-tipo="clientes">
              <div class="icono"><i class="fa-solid fa-users"></i></div>
              <h3>Reporte de Clientes</h3>
          </a>

          <a class="tarjetareportes card-btn" data-tipo="citas">
              <div class="icono"><i class="fa-solid fa-calendar-check"></i></div>
              <h3>Reporte de Citas</h3>
          </a>

          <a class="tarjetareportes card-btn" data-tipo="servicios">
              <div class="icono"><i class="fa-solid fa-scissors"></i></div>
              <h3>Reporte de Servicios</h3>
          </a>

          <a class="tarjetareportes card-btn" data-tipo="ingresos">
              <div class="icono"><i class="fa-solid fa-money-bill-wave"></i></div>
              <h3>Reporte de Ingresos</h3>
          </a>

      </div>

      <!-- MODAL -->
  <div class="modal fade" id="modalReporte" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModal">Generar Reporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formFechas" action="{{ route('reportes.generar') }}">
                    @csrf

                    <label>Fecha inicial</label>
                    <input type="date" name="inicio" class="form-control mb-3" required>

                    <label>Fecha final</label>
                    <input type="date" name="fin" class="form-control mb-3" required>

                    <input type="hidden" id="tipoReporte" name="tipo">

                    <button type="submit" class="btn btn-primary w-100">
                        Generar Reporte
                    </button>
                </form>
            </div>
        </div>
    </div>
  </div>


  <!-- Modal Resultado (Ver / Descargar) -->
  <div class="modal fade" id="modalResultado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reporte listo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <div id="iframeContainer" style="width:100%; height:400px;"></div>
          <div class="d-flex gap-2 justify-content-center mt-2">
            <a id="verReporteBtn" class="btn btn-outline-primary" target="_blank" rel="noopener">Ver</a>
            <a id="descargarReporteBtn" class="btn btn-primary" download>Descargar</a>
          </div>
        </div>
      </div>
    </div>
  </div>




  </section>



      </section>

      <!-- CONFIGURACIÓN -->
      <section id="configuracion" class="mb-5" style="display:none;">
        <h4 class="mb-3">Configuración del Sistema</h4>
        <p>Opciones de personalización del sistema y gestión de roles.</p>
      </section>

    </div>
  </div>

    <!--ssss Scripts -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <style>
      #calendar {
        min-height: 700px;
      }
      .fc-event { 
        cursor: pointer;
      }
      .fc {
        background: white;
        border-radius: 10px;
        padding: 10px;
        
      }

    </style>

    <!--Genera los eventos del calendario, no mover-->
    @php
    $events = $citas->map(function($cita) {
        return [
            'id' => $cita->id,
            'title' => ($cita->servicio->Nom_Servicio ?? 'Sin servicio').' - '.($cita->cliente->nombre ?? 'Sin cliente'),
            'start' => $cita->fecha . 'T' . $cita->hora,
            'backgroundColor' => $cita->estado == 'cancelada' ? '#ff0000' : '#9ef5b0',
            'extendedProps' => [
                'fecha' => $cita->fecha,
                'hora' => $cita->hora,
                'cliente_id' => $cita->cliente_id,
                'servicio_id' => $cita->servicio_id,
                'empleado_id' => $cita->empleado_id,
                'notas' => $cita->notas,
            ],
        ];
    })->toArray();
    @endphp


      <!-- ============================= -->
      <!-- SCRIPT FULLCALENDAR -->
      <!-- ============================= -->
      <script>
      document.addEventListener('DOMContentLoaded', function() {

          const calendarEl = document.getElementById('calendar');
          const modal = new bootstrap.Modal(document.getElementById('citaModal'));
          const form = document.getElementById('formCita');
          const btnEliminar = document.getElementById('btnEliminar');
          let cita_id = null;

          window.calendar = new FullCalendar.Calendar(calendarEl, {
              initialView: 'dayGridMonth',
              locale: 'es',
              selectable: true,
              events: @json($events),

              dateClick(info) {
                  cita_id = null;
                  form.reset();
                  document.getElementById('modalTitulo').innerText = "Agregar Cita";
                  btnEliminar.style.display = 'none';

                  document.getElementById('fechaCita').value = info.dateStr;

                  modal.show();
              },

              eventClick(info) {
                  const e = info.event;
                  const data = e.extendedProps;

                  cita_id = e.id;

                  document.getElementById('modalTitulo').innerText = "Editar Cita";
                  btnEliminar.style.display = 'inline-block';

                  document.getElementById('cita_id').value = e.id;
                  document.getElementById('fechaCita').value = data.fecha;
                  document.getElementById('horaCita').value = data.hora;
                  document.getElementById('clienteCita').value = data.cliente_id;
                  document.getElementById('servicioCita').value = data.servicio_id;
                  document.getElementById('empleadoCita').value = data.empleado_id;
                  document.getElementById('notasCita').value = data.notas ?? '';

                  modal.show();
              }
          });

          window.calendar.render();

          const CSRF = '{{ csrf_token() }}';

          // GUARDAR / ACTUALIZAR
          form.addEventListener('submit', function(e) {
              e.preventDefault();

              const payload = {
                  fecha: form.fechaCita.value,
                  hora: form.horaCita.value,
                  cliente_id: form.clienteCita.value,
                  servicio_id: form.servicioCita.value,
                  empleado_id: form.empleadoCita.value,
                  notas: form.notasCita.value,
              };

              const url = cita_id
                  ? `/admin/paneladmin/citas/${cita_id}`
                  : `/admin/paneladmin/citas`;

              const method = cita_id ? 'PUT' : 'POST';

              fetch(url, {
                  method: method,
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': CSRF,
                      'Accept': 'application/json'
                  },
                  body: JSON.stringify(payload)
              })
              .then(res => res.json())
              .then(data => {
                  if (data.success) {
                      modal.hide();
                      location.reload();
                  } else {
                      console.error(data);
                      alert("Error al guardar la cita");
                  }
              });
          });

          // ELIMINAR
          btnEliminar.addEventListener('click', function() {
              if (!confirm("¿Eliminar esta cita?")) return;

              fetch(`/admin/paneladmin/citas/${cita_id}`, {
                  method: 'DELETE',
                  headers: {
                      'X-CSRF-TOKEN': CSRF,
                      'Accept': 'application/json'
                  }
              })
              .then(res => res.json())
              .then(data => {
                  if (data.success) {
                      modal.hide();
                      location.reload();
                  } else {
                      alert("No se pudo eliminar");
                  }
              });
          });

      });
      </script>

      <!--Scrip del renderizado del calendario-->
      <script>
          function mostrarSeccion(id) {
              const secciones = document.querySelectorAll('#page-content > section');
              secciones.forEach(sec => sec.style.display = 'none');

              const activa = document.getElementById(id);
              if (activa) {
                  activa.style.display = 'block';
                  window.scrollTo({ top: 0, behavior: 'smooth' });
              }

              // Renderizar bien el calendario al abrir su sección
              if (id === 'citas' && window.calendar) {
                  setTimeout(() => window.calendar.render(), 200);
              }

              // ---- Estilos del menú lateral ----
              document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                  link.classList.remove('active', 'bg-light');
                  link.classList.add('text-white');
              });

              const activo = document.querySelector(`.sidebar .nav-link[onclick="mostrarSeccion('${id}')"]`);
              if (activo) {
                  activo.classList.add('bg-light', 'text-dark');
              }

          }

          document.addEventListener('DOMContentLoaded', () => {
              mostrarSeccion('dashboard');
          });
      </script>


      <!--script de los reportes-->
      <script>
        document.addEventListener('DOMContentLoaded', function () {

          // Detecta clic en tarjetas y abre modal de fechas
          document.querySelectorAll(".tarjetareportes").forEach(t => {
            t.addEventListener("click", function () {
              const titulo = this.querySelector("h3").innerText.trim();
              const texto = titulo.toLowerCase();

              let tipo = "";
              if (texto.includes("cliente")) tipo = "clientes";
              else if (texto.includes("cita")) tipo = "citas";
              else if (texto.includes("servicio")) tipo = "servicios";
              else if (texto.includes("ingreso") || texto.includes("venta") || texto.includes("ganancia")) tipo = "ingresos";
              if (!tipo) tipo = texto.replace(/\s+/g, "_");

              document.getElementById("tituloModal").innerText = titulo;
              document.getElementById("tipoReporte").value = tipo;

              // show modal
              new bootstrap.Modal(document.getElementById('modalReporte')).show();
            });
          });

          // Submit del formulario: usar fetch para recibir JSON y mostrar modal resultado
          const form = document.getElementById('formFechas');
          form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Muestra un spinner simple en el botón (opcional)
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Generando...';

            try {
              const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value;

              const res = await fetch(this.action, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': token,
                  'Accept': 'application/json'
                },
                body: formData
              });

              const data = await res.json();

              if (data.success && data.url) {
                const verBtn = document.getElementById('verReporteBtn');
                const descargarBtn = document.getElementById('descargarReporteBtn');
                const iframeContainer = document.getElementById('iframeContainer');

                const url = data.url;

                // Mostrar PDF en el modal con iframe
                iframeContainer.innerHTML = `<iframe src="${url}" width="100%" height="100%" style="border:none;"></iframe>`;

                // Botón "Ver" abre en nueva pestaña
                verBtn.setAttribute('href', url);
                verBtn.setAttribute('target', '_blank');

                // Botón "Descargar" fuerza la descarga
                descargarBtn.setAttribute('href', url);
                const filename = url.split('/').pop();
                descargarBtn.setAttribute('download', filename);

                // Cerrar modal de fechas y abrir modal resultado
                bootstrap.Modal.getInstance(document.getElementById('modalReporte')).hide();
                new bootstrap.Modal(document.getElementById('modalResultado')).show();
              }
              else {
                        alert(data.mensaje || 'Error al generar el reporte');
                      }
                    } catch (err) {
                      console.error(err);
                      alert('Error en la petición, revisa la consola.');
                    } finally {
                      btn.disabled = false;
                      btn.innerHTML = originalText;
                    }
                  });

              });
      </script>
</body>
</html>
