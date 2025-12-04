<!DOCTYPE html>
<html lang="es">

<head>
  <title>Cristy Nails</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" 
        rel="stylesheet">

  <!-- Tus CSS pero con asset() -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styleP.css') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;700&family=Marcellus&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>
<body class="bg-light">
<!-- HEADER -->


<body class="homepage">

    <!-- ========================= NAVBAR ========================= -->
    <nav class="navbar navbar-expand-lg bg-light text-uppercase fs-6 p-3 border-bottom align-items-center">
        <div class="container-fluid">

            <!-- LOGO -->
            <div class="col-auto">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/main-logo.png') }}" alt="logo">
                </a>
            </div>

            <!-- MENU RESPONSIVE -->
            <div class="col-auto">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="menuNav">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">Menú</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                    </div>

                    <div class="offcanvas-body text-center">

                        <ul class="navbar-nav mx-auto">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Inicio</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('agendar') }}">Agendar Cita</a>
                            </li>

                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                                </li>
                            @endguest

                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                        {{ Auth::user()->usuario }}
                                    </a>

                                    <ul class="dropdown-menu">

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
        </div>
    </nav>

    <!-- ========================= CONTENIDO ========================= -->
    <main class="py-4">
        @yield('content')
    </main>

   <!-- ========================= FOOTER ========================= -->
<footer id="footer" class="mt-5">
    <div class="container">
        <div class="row justify-content-between py-5">

            <div class="col-md-3">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/main-logo.png') }}" alt="logo">
                </a>

                <div class="social-links mt-3">
                    <ul class="list-unstyled d-flex gap-3">

                        <li>
                            <a href="https://www.facebook.com/cristysnailsandbeauty/?locale=es_LA" target="_blank" class="text-secondary">
                                <i class="bi bi-facebook fs-4"></i>
                            </a>
                        </li>

                        <li>
                            <a href="https://www.instagram.com/cristys_nails_and_beauty/" target="_blank" class="text-secondary">
                                <i class="bi bi-instagram fs-4"></i>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="col-md-3">
                <h5 class="text-uppercase">Ubicación</h5>
                <a href="https://maps.app.goo.gl/cusgs7mrAf1jf2Fa8" target="_blank">
                    Abrir en Google Maps
                </a>
            </div>

            <div class="col-md-3">
                <h5 class="text-uppercase">Contacto</h5>
                <p><a href="tel:+14803632904">+1 (480) 363-2904</a></p>
            </div>

        </div>
    </div>
</footer>


    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/SmoothScroll.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>


    <script src="{{ asset('js/script.min.js') }}"></script>
    <script>
    new Swiper(".main-swiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
</script>

<script>
  new Swiper(".main-swiper", {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      pagination: {
          el: ".swiper-pagination",
          clickable: true,
      },
      navigation: {
          nextEl: ".icon-arrow.icon-arrow-right",
          prevEl: ".icon-arrow.icon-arrow-left",
      },
      breakpoints: {
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 }
      }
  });
</script>


</body>

</html>
