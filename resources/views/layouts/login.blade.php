@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<head>
    <meta charset="UTF-8">
    <title>Login - Cristy Nails and Beauty</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="login-body d-flex align-items-center vh-100">
    <div class="container">
        <div class="row shadow-lg rounded overflow-hidden">

            <!-- Columna izquierda (formulario) -->
            <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/nailslogo.png') }}" alt="Logo" width="100">
                    <h4 class="mt-3">Cristy Nails and Beauty</h4>
                    <p class="text-muted">Inicia sesión en tu cuenta</p>
                </div>

                <!-- FORMULARIO -->
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email"
                               placeholder="ejemplo@email.com"
                               value="{{ old('email') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password"
                               id="password" placeholder="********" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <small>¿No tienes cuenta? 
                        <a href="{{ url('auth/registro') }}">Regístrate aquí</a>
                    </small>
                </div>
            </div>

            <!-- Imagen derecha -->
            <div class="col-md-6 p-0">
                <img src="{{ asset('img/iniciosesion.jpg') }}"
                     alt="Imagen"
                     class="w-100 h-100"
                     style="object-fit: cover;">
            </div>

        </div>
    </div>

    <!-- SWEETALERT MENSAJES -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Correcto!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#d63384'
            })
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}',
                confirmButtonColor: '#d63384'
            })
        </script>
    @endif

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Credenciales incorrectas',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#d63384'
            })
        </script>
    @endif

</body>
</html>
@endsection
