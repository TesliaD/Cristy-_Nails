<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Empleado</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&family=Marcellus&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Tu CSS -->
  <link rel="stylesheet" href="{{ asset('css/panelempleados.css') }}">

  <style>
    body { background: #f7f7f7; }
    .sidebar { background-color: #8b008b; min-height: 100vh; width: 230px; }
    .sidebar a { color: white; text-decoration: none; }
    .sidebar a:hover { background: rgba(255,255,255,0.2); border-radius: 5px; }
    .fc-event { cursor: pointer; }
    :root {
      --bs-body-font-family: "Jost", Roboto, sans-serif;
      --bs-heading-font-family: "Marcellus", serif;
    }

    body {
      background: #f7f7f7;
      font-family: var(--bs-body-font-family);
    }

    /* Sidebar */
    .sidebar {
      background-color: #b08e6b;
      min-height: 100vh;
      width: 230px;
      font-family: var(--bs-body-font-family);
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 5px;
    }

    .sidebar h5 {
      font-family: var(--bs-heading-font-family);
      color: #fff;
      letter-spacing: 0.5px;
    }

    /* Títulos */
    h1, h2, h3, h4, h5 {
      font-family: var(--bs-heading-font-family);
      color: #333;
    }

    /* Tarjetas */
    .card h5 {
      font-family: var(--bs-heading-font-family);
      font-weight: 600;
      color: #333;
    }

    /* Botones */
    .btn {
      font-family: var(--bs-body-font-family);
      font-weight: 500;
    }

  </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="{{ asset('js/calendario.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar text-white p-3 sidebar">

      <a href="{{ route('dashboard') }}">
  <img src="{{ asset('img/nailslogo1.png') }}" 
       alt="Cristy Nails and Beauty"
       class="d-block mx-auto"
       style="width: 200px; height: auto;">
</a>
        @if(session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: '¡Completado!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
    </script>
    @endif



      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white " onclick="mostrarSeccion('inicio')">
            <i class="bi bi-house-door"></i> Inicio
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white " onclick="mostrarSeccion('citas')">
            <i class="bi bi-calendar-week"></i> Mis Citas
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link text-white " onclick="mostrarSeccion('clientes')">
            <i class="bi bi-person-lines-fill"></i> Clientes
          </a>
        </li>
        <li class="nav-item mt-4">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light w-100 u">
              <i class="bi bi-box-arrow-left"></i> Cerrar sesión
            </button>
          </form>
        </li>
      </ul>
    </div>

    <!-- Contenido -->
    <div class="flex-grow-1 p-4">
      <h3 class="mb-4 fw-semibold">
        <i class="bi bi-person-workspace"></i> Bienvenido, {{ Auth::user()->usuario ?? 'empleado' }}
      </h3>

    <!-- INICIO -->
    <section id="inicio">
      <div class="alert alert-info">
        <strong>¡Buen día!</strong> Aquí puedes consultar tus citas, clientes y servicios del día.

        <div id="alertaCitas" class="mini-alert center-alert">
          @if($totalCitas > 0)
              Tienes {{ $totalCitas ?? 0 }} cita(s) pendiente(s) para hoy
          @else
              No tienes citas para el día de hoy
          @endif
        </div>

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
          </section>
        </div>

        <div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalEventoLabel">Detalles del día</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body" id="contenidoModal"></div>
            </div>
          </div>
        </div>
      </section>

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
            <td>
                <button 
                  class="btn-soft btn-edit"
                  data-id="{{ $cita->id }}"
                  data-fecha="{{ $cita->fecha }}"
                  data-hora="{{ $cita->hora }}"
                  data-servicio="{{ $cita->servicio->Nom_Servicio }}"
                  data-notas="{{ $cita->notas }}"
                  data-costoextra="{{ $cita->costo_extra }}"
                >
                  Modificar Orden
                </button>


                <form action="{{ route('empleado.citas.completar', $cita->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn-soft btn-complete">Completar</button>
                </form>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">No tienes citas</td>
          </tr>
        @endforelse
      </tbody>

      </table>



      <!-- ============================= -->
      <!-- MODAL PARA EMPLEADO -->
      <!-- ============================= -->
      <div class="modal fade" id="citaEmpleadoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form id="formCitaEmpleado" onsubmit="event.preventDefault();">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title">Detalles de la Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <input type="hidden" id="cita_id_empleado" name="cita_id">

                <!-- Solo lectura (difuminado) -->
                <div class="mb-3">
                  <label class="form-label">Fecha</label>
                  <div class="bloqueado-wrapper">
                    <input type="text" id="fechaCitaEmpleado" class="form-control" readonly>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Hora</label>
                  <div class="bloqueado-wrapper">
                    <input type="text" id="horaCitaEmpleado" class="form-control" readonly>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Servicio</label>
                  <div class="bloqueado-wrapper">
                    <input type="text" id="servicioCitaEmpleado" class="form-control" readonly>
                  </div>
                </div>

                                <!-- NUEVO: costo extra por servicio adicional -->
                <div class="mb-3">
                  <label class="form-label">Costo extra</label>
                  <input 
                    type="number" 
                    class="form-control" 
                    id="costoExtra" 
                    name="costo_extra" 
                    placeholder="Ejemplo: 50">
                  <div class="form-text">Si agregó un diseño extra, retiro, etc.</div>
                </div>


                <!-- Campo editable -->
                <div class="mb-3">
                  <label class="form-label">Notas</label>
                  <textarea name="notas" id="notasCitaEmpleado" class="form-control" rows="3"></textarea>
                </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarCambios">Guardar cambios</button>

              </div>
            </form>
          </div>
        </div>
      </div>



    </section>

      <!-- CLIENTES -->
      <section id="clientes" style="display:none;">
        <h4><i class="bi bi-people-fill"></i> Mis Clientes</h4>
        <table class="table table-striped mt-3">
          <thead class="">
            <tr>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Email</th>
            </tr>
          </thead>
          @php $usuariosMostrados = []; @endphp
          <tbody>
            @foreach($citas as $cita)
              @php $email = $cita->cliente->usuario->email ?? null; @endphp
              @if($email && !in_array($email, $usuariosMostrados))
                <tr>
                  <td>{{ $cita->cliente->nombre }}</td>
                  <td>{{ $cita->cliente->telefono }}</td>
                  <td>{{ $email }}</td>
                </tr>
                @php $usuariosMostrados[] = $email; @endphp
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
    mostrarAlertaCitas();
    document.getElementById('dashboard').style.display = 'block';
    document.getElementById('calendario').style.display = 'block';
  } else {
    const seccionActual = document.getElementById(id);
    if (seccionActual) seccionActual.style.display = 'block';
  }
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

<script>
document.addEventListener("DOMContentLoaded", () => {
    mostrarAlertaCitas();
    // --- Abrir modal con datos ---
    const botones = document.querySelectorAll(".btn-edit");

    botones.forEach(btn => {
        btn.addEventListener("click", function () {

            let id = this.dataset.id;

            let notaTexto = "";
            let costoExtra = "";

            if (this.dataset.notas) {
                try {
                    let notaObj = JSON.parse(this.dataset.notas);

                    // Texto de notas
                    notaTexto = notaObj.notas || "";

                    // Costo extra si existe
                    costoExtra = notaObj.extra || "";

                } catch (e) {
                    // Por si no es JSON
                    notaTexto = this.dataset.notas;
                }
            }


            document.getElementById("cita_id_empleado").value = id;
            document.getElementById("fechaCitaEmpleado").value = this.dataset.fecha;
            document.getElementById("horaCitaEmpleado").value = this.dataset.hora;
            document.getElementById("servicioCitaEmpleado").value = this.dataset.servicio;
            document.getElementById("notasCitaEmpleado").value = notaTexto;
            document.getElementById("costoExtra").value = costoExtra;

            const modal = new bootstrap.Modal(document.getElementById("citaEmpleadoModal"));
            modal.show();
        });
    });


    document.getElementById("btnGuardarCambios").addEventListener("click", async () => {
      let id = document.getElementById("cita_id_empleado").value;

      let formData = new FormData();
      formData.append("_token", "{{ csrf_token() }}");
      formData.append("notas", document.getElementById("notasCitaEmpleado").value);
      formData.append("costo_extra", document.getElementById("costoExtra").value);

      try {
        let respuesta = await fetch(`/empleado/citas/${id}/actualizar`, {
            method: "POST",
            body: formData
        });

        if (respuesta.ok) {
            Swal.fire({
                icon: 'success',
                title: '¡Se guardaron los cambios!',
                showConfirmButton: false,
                timer: 1500
              }).then(() => {

              window.location.href = "/empleado/panelempleado";
          });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar cambios',
                showConfirmButton: true
            });
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar cambios',
            showConfirmButton: true
        });
    }

  });
});





</script>


</body>
</html>
