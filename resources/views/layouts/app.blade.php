<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cristy Nails</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Iconos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!--Carpeta de CSS con archivo styles.css-->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-light">
<!-- HEADER -->
<header class="bg-white shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <!-- Logo -->
        <a href="{{ url('/') }}">
            <img class="img-fluid" src="{{ asset('img/nailslogo.jpg') }}" alt="Cristy Nails and Beauty" style="max-height: 80px;">
        </a>

        <!-- Ícono de usuario -->
        <div class="dropdown">
            @auth
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-4 me-2 text-danger"></i>
                    <span>{{ Auth::user()->nombre }}</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    @if(Auth::user()->rol === 'admin')
                        <!-- Opciones del administrador -->
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Panel de Administración</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.usuarios') }}">Gestión de Usuarios</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.citas') }}">Gestión de Citas</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.reportes') }}">Reportes</a></li>
                    @else
                        <!-- Opciones del cliente -->
                        <li><a class="dropdown-item" href="{{ route('panelclientes') }}">Mi Panel</a></li>
                        <li><a class="dropdown-item" href="{{ route('agendar') }}">Agendar Cita</a></li>
                        <li><a class="dropdown-item" href="{{ route('login') }}">Mis Citas</a></li>      
                    
                    @endif

                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-danger d-flex align-items-center">
                    <i class="bi bi-person fs-4 me-2"></i> Iniciar Sesión
                </a>
            @endauth
        </div>
    </div>
</header>


    <!-- NAV -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #ff7eb3, #ff758c);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Cristy Nails 💅</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route('dashboard') }}">Inicio</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route('sobrenosotros') }}">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route('agendar') }}">Agendar Cita</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN -->
    <main class="container my-5">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3 mt-6">
        <p class="mb-0">
            Cristy Nails and Beauty - &copy; {{ date('Y') }} Todos los derechos reservados
        </p>
    </footer>

    <!-- Bootstrap 5 JS (con Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
