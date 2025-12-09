<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'Cristy Nails')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body class="d-flex flex-column min-vh-100">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg shadow-sm py-3 sticky-top nav-background">
    <div class="container">

      <a class="navbar-brand" href="/">
        <img src="{{ asset('img/main-logo.png') }}" height="50">
      </a>

      <button class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="offcanvas offcanvas-end" id="menu">
        <div class="offcanvas-header">
          <h5 class="fw-bold">Menú</h5>
          <button class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">

          <ul class="navbar-nav ms-auto align-items-center gap-3">
            <li><a href="{{ route('dashboard') }}" class="nav-link">Inicio</a></li>
            <li><a href="{{ route('agendar') }}" class="nav-link">Agendar Cita</a></li>
            <li><a href="{{ route('sobrenosotros') }}" class="nav-link">Sobre Nosotros</a></li>

            @guest
              <li><a href="{{ route('login') }}" class="btn btn-primary px-3 text-white">Iniciar Sesión</a></li>
            @endguest

            @auth
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->usuario }}</a>
                <ul class="dropdown-menu shadow">

                  @if(Auth::user()->rol === 'admin')
                    <li><a class="dropdown-item" href="{{ route('paneladmin') }}">Panel Admin</a></li>
                  @endif

                  @if(Auth::user()->rol === 'empleado')
                    <li><a class="dropdown-item" href="{{ route('panelempleado') }}">Panel Empleado</a></li>
                  @endif

                  @if(Auth::user()->rol === 'cliente')
                    <li><a class="dropdown-item" href="{{ route('panelcliente.index') }}">Mi Panel</a></li>
                  @endif

                  <li><hr class="dropdown-divider"></li>

                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger">Cerrar Sesión</button>
                    </form>
                  </li>
                </ul>
              </li>
            @endauth

          </ul>

        </div>
      </div>

    </div>
  </nav>

  <!-- CONTENIDO -->
  <main class="content-wrapper py-5 flex-fill">
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="footer-area shadow-sm">
    <div class="container">
      <div class="row g-4 justify-content-between">

        <div class="col-md-4">
          <img src="{{ asset('img/main-logo.png') }}" height="55">
          <p class="text-muted mt-3">Belleza • Estilo • Confianza</p>

          <div class="d-flex gap-3">
            <a href="#"><i class="bi bi-facebook fs-4"></i></a>
            <a href="#"><i class="bi bi-instagram fs-4"></i></a>
          </div>
        </div>

        <div class="col-md-3">
          <h5 class="fw-bold mb-2">Ubicación</h5>
          <a href="#" target="_blank">Abrir en Google Maps</a>
        </div>

        <div class="col-md-3">
          <h5 class="fw-bold mb-2">Contacto</h5>
          <p><a href="tel:+14803632904">+1 (480) 363-2904</a></p>
        </div>

      </div>
    </div>
  </footer>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  @stack('scripts')

</body>
</html>
