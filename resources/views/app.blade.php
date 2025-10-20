<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cristy Nails</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-light">

    <!-- HEADER -->
    <header class="bg-white shadow-sm">
        <div class="container d-flex justify-content-center py-3">
            <a href="{{ url('/') }}">
                <img class="img-fluid" src="{{ asset('img/nailslogo.jpg') }}" alt="Cristy Nails and Beauty" style="max-height: 80px;">
            </a>
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
                        <a class="nav-link text-white fw-semibold" href="{{ route(name: 'dashboard') }}">Inicio</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route(name:'sobrenosotros') }}">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route(name: 'login') }}">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route(name: 'agendar') }}">Agendar Cita</a>
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
