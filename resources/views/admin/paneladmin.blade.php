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

  <!--Script de alertas-->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}">

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
                              <button class="btn btn-danger btn-sm eliminarEmpleadoBtn"
                                      data-id="{{ $empleado->id }}">
                                  <i class="bi bi-trash"></i>
                              </button>

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
                  <form action="{{ route('empleados.store') }}" method="POST" id="formNuevoEmpleado">
                      @csrf

                      <div class="modal-header">
                          <h5 class="modal-title">Agregar Empleado</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">

                          <div class="mb-3">
                              <label class="form-label">Usuario</label>
                              <input name="usuario" class="form-control soloLetras" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input type="email" name="email" class="form-control validarEmail" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Contraseña</label>
                              <input type="password" name="password" class="form-control" required minlength="6">
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
                              <input name="usuario" id="edit_emp_usuario" class="form-control soloLetras" required>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input type="email" name="email" id="edit_emp_email" class="form-control validarEmail" required>
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
                              <input type="password" name="password" class="form-control" minlength="6"
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

      <!--Alerta para el guuardado del modal-->
      <script>
      document.addEventListener('DOMContentLoaded', () => {

          const form = document.getElementById('editarEmpleadoForm');

          form.addEventListener('submit', function(event) {
              event.preventDefault();

              Swal.fire({
                  title: "Guardando cambios...",
                  text: "Por favor espera",
                  icon: "info",
                  showConfirmButton: false,
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  timer: 1200
              });

              setTimeout(() => {
                  form.submit(); 
              }, 1200);
          });

      });
      </script>


      <!-- SCRIPT MODAL EDICIÓN CON SWEETALERT2 -->
      <script>
      document.addEventListener('DOMContentLoaded', () => {

          let modal = new bootstrap.Modal(document.getElementById('editarEmpleadoModal'));
          let form = document.getElementById('editarEmpleadoForm');

          document.querySelectorAll('.editarEmpleadoBtn').forEach(btn => {
              btn.addEventListener('click', () => {

                  Swal.fire({
                      title: "Editar empleado",
                      text: "¿Deseas editar la información de este empleado?",
                      icon: "question",
                      showCancelButton: true,
                      confirmButtonText: "Sí, editar",
                      cancelButtonText: "Cancelar"
                  }).then(result => {
                      if (result.isConfirmed) {

                          // Establecer acción correcta
                          form.action = `/admin/empleados/${btn.dataset.id}`;

                          // Rellenar datos
                          document.getElementById('edit_emp_usuario').value = btn.dataset.usuario;
                          document.getElementById('edit_emp_email').value = btn.dataset.email;
                          document.getElementById('edit_emp_rol').value = btn.dataset.rol;

                          // Mostrar modal
                          modal.show();
                      }
                  });

              });
          });

      });
      </script>



     <script>
      document.querySelectorAll('.eliminarEmpleadoBtn').forEach(btn => {
          btn.addEventListener('click', function () {

              let id = this.dataset.id;

              Swal.fire({
                  title: "¿Eliminar empleado?",
                  text: "Esta acción no se puede deshacer",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonText: "Sí, eliminar",
                  cancelButtonText: "Cancelar"
              }).then(result => {
                  if (result.isConfirmed) {

                      let form = document.createElement('form');
                      form.method = 'POST';
                      form.action = `/admin/empleados/${id}`; 

                      let token = document.createElement('input');
                      token.type = 'hidden';
                      token.name = '_token';
                      token.value = '{{ csrf_token() }}';

                      let method = document.createElement('input');
                      method.type = 'hidden';
                      method.name = '_method';
                      method.value = 'DELETE';

                      form.appendChild(token);
                      form.appendChild(method);

                      document.body.appendChild(form);
                      form.submit();
                  }
              });

          });
      });
      </script>
      <!-- VALIDACIONES -->
      <script>
      // Solo letras
      document.querySelectorAll('.soloLetras').forEach(input => {
          input.addEventListener('input', () => {
              input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, "");
          });
      });

      // Validación email básica
      document.querySelectorAll('.validarEmail').forEach(input => {
          input.addEventListener('input', () => {
              if (!input.value.includes("@")) {
                  input.setCustomValidity("Debe ser un correo válido");
              } else {
                  input.setCustomValidity("");
              }
          });
      });
      </script>

      <!-- BUSCADOR EMPLEADOS -->
      <script>
      document.getElementById('buscarEmpleado').addEventListener('keyup', function () {
          let filtro = this.value.toLowerCase();
          let filas = document.querySelectorAll('#tablaEmpleados tbody tr');

          filas.forEach(fila => {
              let texto = fila.innerText.toLowerCase();
              fila.style.display = texto.includes(filtro) ? '' : 'none';
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
              <form id="formNuevoCliente" action="{{ route('clientes.store') }}" method="POST">
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
                  <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline formEliminarCliente">
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
        <!--CHECAR INPUTS PORQUE SON PARA VALIDAR DATOS, SE PUEDEN REUTILIZAR MAS ADELANTE EN LOS DEMAS MODALES-->
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

                    <!--El campo Usuario sin espacios, solo letras y números-->
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Usuario</label>
                      <input type="text" name="usuario" id="edit_usuario" class="form-control" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g,'');">
                    </div>
                    
                    <!--El campo de nombre solo acepta letras y espacios-->
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Nombre</label>
                      <input type="text" name="nombre" id="edit_nombre" class="form-control" required oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g,'');">
                    </div>
                  </div>
                  <div class="row">
                    <!--El campo Email el navegador valida automáticamente-->
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <!--Campo de Teléfono solo números, máximo 12-->
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Teléfono</label>
                      <input type="text" name="telefono" id="edit_telefono" maxlength="12" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g,'');">
                    </div>
                  </div>

                  <!--El campo de Dirección permitir letras, números, # , . , - , espacios-->
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Dirección</label>
                      <input type="text" name="direccion" id="edit_direccion" class="form-control" oninput="this.value = this.value.replace(/[^a-zA-Z0-9#\-\.\s]/g,'');">
                    </div>
                    <!--No permitir fechas futuras-->
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Fecha nacimiento</label>
                      <input type="date" name="fecha_nacimiento" id="edit_fecha" class="form-control" max="{{ date('Y-m-d') }}">
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

                  form.action = `/admin/paneladmin/clientes/${btn.dataset.id}`;

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


      <!--Script de las validaciones-->
      <script>
      document.addEventListener("DOMContentLoaded", () => {

          // Bloquea pegado inválido en teléfono
          document.querySelectorAll("input[name='telefono']").forEach(input => {
              input.addEventListener("paste", e => {
                  let paste = (e.clipboardData || window.clipboardData).getData('text');
                  if (!/^[0-9]+$/.test(paste)) e.preventDefault();
              });
          });

          // Evita caracteres inválidos en usuario
          document.querySelectorAll("input[name='usuario']").forEach(input => {
              input.addEventListener("paste", e => {
                  let paste = (e.clipboardData || window.clipboardData).getData('text');
                  if (!/^[a-zA-Z0-9]+$/.test(paste)) e.preventDefault();
              });
          });

      });
      </script>

      
      <!--Alerta para eliminar Cliente-->
      <script>
      document.querySelectorAll('.formEliminarCliente').forEach(form => {
          form.addEventListener('submit', function(e){
              e.preventDefault();

              Swal.fire({
                  title: "¿Eliminar cliente?",
                  text: "Esta acción no puede deshacerse.",
                  icon: "error",
                  showCancelButton: true,
                  confirmButtonText: "Sí, eliminar",
                  cancelButtonText: "Cancelar"
              }).then((result) => {
                  if (result.isConfirmed) {
                      this.submit();
                  }
              });
          });
      });
      </script>

      <!--Alerta para Editar cliente-->
      <script>
      document.getElementById('editarClienteForm').addEventListener('submit', function(e){
          e.preventDefault();

          Swal.fire({
              title: "¿Guardar cambios?",
              text: "El cliente será actualizado.",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "Sí, actualizar",
              cancelButtonText: "Cancelar"
          }).then((result) => {
              if (result.isConfirmed) {
                  this.submit();
              }
          });
      });
      </script>

      <!--Script para guuardar un cliente nuevo ALERTA-->
      <script>
      document.getElementById('formNuevoCliente').addEventListener('submit', function(e) {
          e.preventDefault(); 

          Swal.fire({
              title: "¿Agregar cliente?",
              text: "Se guardará un nuevo cliente en el sistema.",
              icon: "question",
              showCancelButton: true,
              confirmButtonText: "Sí, guardar",
              cancelButtonText: "Cancelar"
          }).then((result) => {
              if (result.isConfirmed) {
                  this.submit();
              }
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
                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm"><i class="bi bi-bookmark-heart-fill"></i> Guardar Servicio
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
                            <button type="submit" class="btn btn-sm btn-primary me-1"><i class="bi bi-floppy-fill"></i></button>
                        </form>
                        <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline;">
                          @csrf @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este servicio?')"><i class="bi bi-trash3-fill"></i></button>
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
                  <!-- select dinámico -->
                  <select name="hora" id="horaCita" class="form-select" required>
                    <option value="">Selecciona fecha y servicio</option>
                  </select>
                  <div id="horaHelp" class="form-text">Las horas se calculan según la duración del servicio y disponibilidad del día.</div>
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
                      <option value="{{ $s->id }}" data-duracion="{{ $s->Duracion }}">{{ $s->Nom_Servicio }}</option>
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

          <a class="tarjetareportes card-btn" data-tipo="backup">
            <div class="icono"><i class="fa-solid fa-database"></i></div>
            <h3>Respaldo (Backup)</h3>
          </a>


      </div>

      <!-- MODAL REPORTES -->
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


  <!-- MODAL BACKUP -->
  <div class="modal fade" id="modalBackup" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">

              <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title">Gestión de Backups</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">

                  <!-- Lista de backups -->
                  <h6 class="fw-bold mb-2">Backups disponibles</h6>

                  <div id="lista-backups"
                      style="border: 1px solid #ddd; border-radius: 6px; padding: 10px; max-height: 250px; overflow-y: auto;">
                      <p class="text-center text-muted">Cargando backups...</p>
                  </div>

                  <!-- Acciones -->
                  <div class="mt-4">
                      <button id="btnGenerarBackup" type="button" class="btn btn-success w-100 mb-2">
                          <i class="fa-solid fa-plus"></i> Generar Backup
                      </button>

                      <button id="btnDescargarBackup" type="button" class="btn btn-primary w-100 mb-2">
                        <i class="fa-solid fa-download"></i> Descargar Backup
                      </button>

                      <button id="btnRestaurarBackup" type="button" class="btn btn-warning w-100 mb-2" disabled>
                          <i class="fa-solid fa-rotate-left"></i> Restablecer Backup Seleccionado
                      </button>

                      <button id="btnEliminarBackup" type="button" class="btn btn-danger w-100" disabled>
                          <i class="fa-solid fa-trash"></i> Eliminar Backup Seleccionado
                      </button>
                  </div>
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
            
      <!-- SCRIPT para manejar calendario + modal + horas disponibles -->
      <script>
      document.addEventListener('DOMContentLoaded', function() {
          const calendarEl = document.getElementById('calendar');
          const modal = new bootstrap.Modal(document.getElementById('citaModal'));
          const form = document.getElementById('formCita');
          const btnEliminar = document.getElementById('btnEliminar');
          let cita_id = null;

          const fechaInput = document.getElementById('fechaCita');
          const servicioSelect = document.getElementById('servicioCita');
          const horaSelect = document.getElementById('horaCita');

          // ==========================
          // FULLCALENDAR
          // ==========================
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

                  fechaInput.value = info.dateStr;
                  horaSelect.innerHTML = '<option value="">Selecciona servicio</option>';

                  modal.show();
              },

              eventClick(info) {
                  const e = info.event;
                  const data = e.extendedProps;

                  cita_id = e.id;
                  document.getElementById('modalTitulo').innerText = "Editar Cita";
                  btnEliminar.style.display = 'inline-block';

                  document.getElementById('cita_id').value = e.id;
                  fechaInput.value = data.fecha;

                  servicioSelect.value = data.servicio_id;
                  cargarHorasDisponibles().then(() => {
                      horaSelect.value = data.hora;

                      if (!Array.from(horaSelect.options).some(o => o.value === data.hora)) {
                          const opt = document.createElement('option');
                          opt.value = data.hora;
                          opt.text = data.hora;
                          horaSelect.insertBefore(opt, horaSelect.firstChild);
                          horaSelect.value = data.hora;
                      }
                  });

                  document.getElementById('clienteCita').value = data.cliente_id;
                  document.getElementById('empleadoCita').value = data.empleado_id;
                  document.getElementById('notasCita').value = data.notas ?? '';

                  modal.show();
              }
          });

          window.calendar.render();

          const CSRF = '{{ csrf_token() }}';

          // ==========================
          // CARGAR HORAS DISPONIBLES
          // ==========================
          async function cargarHorasDisponibles() {
              horaSelect.innerHTML = '<option>Cargando...</option>';

              const fecha = fechaInput.value;
              const servicioId = servicioSelect.value;

              if (!fecha || !servicioId) {
                  horaSelect.innerHTML = '<option value="">Selecciona fecha y servicio</option>';
                  return [];
              }

              try {
                  const params = new URLSearchParams({ fecha, servicio_id: servicioId });
                  const res = await fetch(`/admin/citas/horas-disponibles?${params}`, {
                      headers: { 'Accept': 'application/json' }
                  });

                  if (!res.ok) {
                      horaSelect.innerHTML = '<option>Error al cargar</option>';
                      return [];
                  }

                  const data = await res.json();
                  horaSelect.innerHTML = '';

                  if (data.length === 0) {
                      horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                      return [];
                  }

                  data.forEach(h => {
                      let opt = document.createElement('option');
                      opt.value = h;
                      opt.text = h;
                      horaSelect.appendChild(opt);
                  });

                  return data;

              } catch (err) {
                  console.error(err);
                  horaSelect.innerHTML = '<option>Error de red</option>';
                  return [];
              }
          }

          fechaInput.addEventListener('change', cargarHorasDisponibles);
          servicioSelect.addEventListener('change', cargarHorasDisponibles);

          // ==========================
          // GUARDAR / EDITAR CITA
          // ==========================
          form.addEventListener('submit', async function(e) {
              e.preventDefault();

              const payload = {
                  fecha: fechaInput.value,
                  hora: horaSelect.value,
                  cliente_id: document.getElementById('clienteCita').value,
                  servicio_id: servicioSelect.value,
                  empleado_id: document.getElementById('empleadoCita').value,
                  notas: document.getElementById('notasCita').value
              };

              const url = cita_id ? `/admin/paneladmin/citas/${cita_id}` : `/admin/paneladmin/citas`;
              const method = cita_id ? 'PUT' : 'POST';

              try {
                  const res = await fetch(url, {
                      method,
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': CSRF,
                          'Accept': 'application/json'
                      },
                      body: JSON.stringify(payload)
                  });

                  const data = await res.json();

                  if (res.status === 422) {
                      Swal.fire({
                          icon: "warning",
                          title: "Validación",
                          text: Object.values(data.errors).flat().join("\n")
                      });
                      return;
                  }

                  if (data.success) {
                      modal.hide();

                      Swal.fire({
                          icon: "success",
                          title: cita_id ? "Cita actualizada" : "Cita creada",
                          timer: 1500,
                          showConfirmButton: false
                      }).then(() => location.reload());
                  } else {
                      Swal.fire({
                          icon: "error",
                          title: "Error",
                          text: data.message ?? "No se pudo guardar la cita"
                      });
                  }

              } catch (err) {
                  console.error(err);

                  Swal.fire({
                      icon: "error",
                      title: "Error de servidor",
                      text: "Revisa la consola"
                  });
              }
          });

          // ==========================
          // ELIMINAR CITA
          // ==========================
          btnEliminar.addEventListener('click', function() {

              Swal.fire({
                  title: "¿Eliminar esta cita?",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonText: "Sí, eliminar",
                  cancelButtonText: "Cancelar"
              }).then(resultado => {
                  if (!resultado.isConfirmed) return;

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
                          Swal.fire({
                              icon: "success",
                              title: "Cita eliminada",
                              timer: 1500,
                              showConfirmButton: false
                          }).then(() => location.reload());
                      } else {
                          Swal.fire({
                              icon: "error",
                              title: "Error",
                              text: "No se pudo eliminar"
                          });
                      }
                  });
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

      // VALIDACION FECHA
    const inicio = document.querySelector('input[name="inicio"]');
    const fin = document.querySelector('input[name="fin"]');

    // Cuando cambia la fecha inicial → actualizar min en la final
    inicio.addEventListener('change', () => {
        fin.min = inicio.value;
    });

    // Cuando cambia la fecha final → actualizar max en la inicial
    fin.addEventListener('change', () => {
        inicio.max = fin.value;
    });

    // Validación antes de enviar (sin borrar valores)
    document.getElementById('formFechas').addEventListener('submit', function (e) {

        // Si falta alguna fecha
        if (!inicio.value || !fin.value) {
            e.preventDefault();
            alert("Debes seleccionar ambas fechas.");
            return;
        }

        // Validar que inicio <= fin
        if (inicio.value > fin.value) {
            e.preventDefault();
            alert("La fecha inicial no puede ser mayor que la fecha final.");
            return;
        }
    });



      // Detecta clic en tarjetas y abre modal correspondiente
      document.querySelectorAll(".tarjetareportes").forEach(t => {
        t.addEventListener("click", function () {

          const titulo = this.querySelector("h3").innerText.trim();
          const texto = titulo.toLowerCase();

          // PRIORIDAD: usar data-tipo si existe
          let tipo = this.dataset.tipo || "";

          // Si no trae data-tipo, detectar por texto
          if (!tipo) {
            if (texto.includes("cliente")) tipo = "clientes";
            else if (texto.includes("cita")) tipo = "citas";
            else if (texto.includes("servicio")) tipo = "servicios";
            else if (texto.includes("ingreso") || texto.includes("venta") || texto.includes("ganancia"))
              tipo = "ingresos";
            else if (texto.includes("backup") || texto.includes("respaldo"))
              tipo = "backup";
            else
              tipo = texto.replace(/\s+/g, "_");
          }

          // MODAL DEL BACKUN
          if (tipo === "backup") {
            new bootstrap.Modal(document.getElementById('modalBackup')).show();
            return; // OBLIGATORIO
          }

          // Modo normal de reportes
          document.getElementById("tituloModal").innerText = titulo;
          document.getElementById("tipoReporte").value = tipo;

          new bootstrap.Modal(document.getElementById('modalReporte')).show();
        });
      });


      // Submit del formulario → fetch → mostrar modal resultado
      const form = document.getElementById('formFechas');
      form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Spinner en botón
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Generando...';

        try {
          const token =
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]').value;

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

            // Mostrar PDF en iframe
            iframeContainer.innerHTML =
              `<iframe src="${url}" width="100%" height="100%" style="border:none;"></iframe>`;

            // Botón Ver
            verBtn.setAttribute('href', url);
            verBtn.setAttribute('target', '_blank');

            // Botón Descargar
            const filename = url.split('/').pop();
            descargarBtn.setAttribute('href', url);
            descargarBtn.setAttribute('download', filename);

            // Cerrar modal de fechas y abrir modal de resultado
            bootstrap.Modal.getInstance(document.getElementById('modalReporte')).hide();
            new bootstrap.Modal(document.getElementById('modalResultado')).show();
          } else {
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


      // ---- SISTEMA DE BACKUPS ----

      // Cuando se abre el modalBackup, cargar la lista
      document.getElementById("modalBackup").addEventListener("shown.bs.modal", cargarBackups);

      let backupSeleccionado = null;

      function cargarBackups() {
          const contenedor = document.getElementById("lista-backups");
          contenedor.innerHTML = `<p class="text-center text-muted">Cargando backups...</p>`;

          fetch("/admin/backup/lista")
              .then(res => res.json())
              .then(data => {
                  if (!data || data.length === 0) {
                      contenedor.innerHTML = `<p class="text-center text-muted">No hay backups disponibles.</p>`;
                      return;
                  }

                  contenedor.innerHTML = "";

                  data.forEach(nombre => {
                      const item = document.createElement("div");
                      item.className = "backup-item p-2 mb-2 border rounded";
                      item.style.cursor = "pointer";
                      item.innerText = nombre;

                      item.addEventListener("click", function () {
                          document.querySelectorAll(".backup-item").forEach(i => {
                              i.classList.remove("bg-primary", "text-white");
                          });

                          this.classList.add("bg-primary", "text-white");
                          backupSeleccionado = nombre;

                          document.getElementById("btnRestaurarBackup").disabled = false;
                          document.getElementById("btnEliminarBackup").disabled = false;
                      });

                      contenedor.appendChild(item);
                  });
              });
      }


      // --- Generar Backup ---
      document.getElementById("btnGenerarBackup").addEventListener("click", function () {

          confirmarAccion("¿Generar un nuevo backup?", () => {

              fetch('/admin/backup/generar')
              .then(res => res.json())
              .then(data => {
                  if (!data.success) {
                      mostrarAlerta("Error: " + data.error, "error");
                      return;
                  }

                  mostrarAlerta(data.message);
                  cargarBackups();
              })

          });

      });

      // --- Descargar Backup ---
      document.getElementById("btnDescargarBackup").addEventListener("click", function () {
      if (!backupSeleccionado) return mostrarAlerta("Selecciona un backup", "error");

      // Asegurarnos que solo sea el nombre del archivo
      let archivo = backupSeleccionado.split("/").pop();

      window.location.href = "/admin/backup/descargar/" + archivo;
    });




      // --- Restaurar Backup ---
      document.getElementById("btnRestaurarBackup").addEventListener("click", function () {

          if (!backupSeleccionado) return mostrarAlerta("Selecciona un backup", "error");

          confirmarAccion(
              "¿Restaurar este backup? Esto reemplazará toda la base de datos.",
              () => {
                  fetch("/admin/backup/restaurar", {
                  method: "POST",
                  headers: {
                      "Content-Type": "application/json",
                      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                  },
                  body: JSON.stringify({ archivo: backupSeleccionado })
              })
              .then(async res => {
                  const data = await res.json().catch(() => null);

                  if (!data) {
                      mostrarAlerta("Error inesperado (el servidor devolvió HTML).", "error");
                      return;
                  }

                  if (!data.success) {
                      mostrarAlerta("Error: " + data.error, "error");
                      return;
                  }

                  mostrarAlerta(data.message);
              });


              }
          );
      });


      // --- Eliminar Backup ---
      document.getElementById("btnEliminarBackup").addEventListener("click", function () {

          if (!backupSeleccionado) return mostrarAlerta("Selecciona un backup", "error");

          confirmarAccion(
              "¿Eliminar este backup? Esta acción no se puede deshacer.",
              () => {
                  fetch("/admin/backup/eliminar", {
                  method: "DELETE",
                  headers: {
                      "Content-Type": "application/json",
                      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                      "X-Requested-With": "XMLHttpRequest"
                  },
                  body: JSON.stringify({ archivo: backupSeleccionado })
              })
              .then(res => res.text()) // ← CAMBIO
              .then(() => {
                  mostrarAlerta("Backup eliminado.");
                  cargarBackups();
              })
              .catch(err => {
                  mostrarAlerta("Error: " + err.message, "error");
              });


              }
          );
      });


      // ===== Función simple y bonita para confirmar acciones =====
    function confirmarAccion(mensaje, callback) {
        const modal = document.createElement("div");
        modal.className = "mini-confirm";

        modal.innerHTML = `
            <div class="mini-confirm-box">
                <p>${mensaje}</p>
                <div class="mini-confirm-btns">
                    <button class="btn-si">Sí</button>
                    <button class="btn-no">No</button>
                </div>
            </div>
        `;

      document.body.appendChild(modal);

        modal.querySelector(".btn-si").onclick = () => {
            modal.remove();
            if (callback) callback();
        };

        modal.querySelector(".btn-no").onclick = () => {
            modal.remove();
        };
    }

    // ===== Alerta simple =====
    function mostrarAlerta(mensaje, tipo = "ok") {
        const div = document.createElement("div");
        div.className = `mini-alert mini-${tipo}`;
        div.textContent = mensaje;

        document.body.appendChild(div);

        setTimeout(() => div.classList.add("show"), 10);

        setTimeout(() => {
            div.classList.remove("show");
            setTimeout(() => div.remove(), 300);
        }, 2500);
    }





    });
  </script>



      <!--ALERTA DE ÉXITO AL REGRESAR DEL CONTROLADOR-->
      @if(session('success'))
      <script>
      Swal.fire({
          title: "¡Éxito!",
          text: "{{ session('success') }}",
          icon: "success",
          confirmButtonText: "Aceptar"
      });
      </script>
      @endif


</body>
</html>
