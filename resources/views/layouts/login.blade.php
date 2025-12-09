@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<head>
    <meta charset="UTF-8">
    <title>Login - Cristy Nails and Beauty</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        /* Fondo suave rosado */
        body {
            background-color: #f8f3f5 !important;
        }

        /* Contenedor grande */
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 85vh;
        }

        /* Tarjeta principal más grande */
        .login-card {
            max-width: 1200px;
            width: 100%;
            min-height: 520px;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transform: scale(1.02);
            transition: 0.3s;
        }

        .login-card:hover {
            transform: scale(1.025);
        }

        /* Imagen derecha */
        .login-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Inputs más grandes */
        .form-control {
            padding: 14px 16px;
            font-size: 1.05rem;
            border-radius: 10px;
        }

        /* Botón principal */
        .btn-primary {
            background-color: #d57492 !important;
            border-color: #d57492 !important;
            padding: 12px;
            font-size: 1.07rem;
            border-radius: 10px;
            transition: .2s;
        }

        .btn-primary:hover {
            background-color: #c85a7e !important;
        }

        /* Enlaces */
        a {
            color: #d57492;
            font-weight: 600;
        }

        a:hover {
            color: #b54e6e;
        }
    </style>
</head>

<body class="login-body">
    <div class="login-wrapper container">
        <div class="row login-card">

            <!-- Columna izquierda (formulario) -->
            <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center">

                <div class="text-center mb-4">
                    <img src="{{ asset('img/nailslogo.png') }}" alt="Logo" width="110">
                    <h4 class="mt-3" style="color:#b35571; font-weight:700;">
                        Cristy Nails and Beauty
                    </h4>
                    <p class="text-muted">Inicia sesión en tu cuenta</p>
                </div>

                <!-- FORMULARIO -->
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email"
                               placeholder="ejemplo@email.com"
                               value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Contraseña</label>
                        <input type="password" class="form-control" name="password"
                               id="password" placeholder="********" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            Ingresar
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <small>¿No tienes cuenta?
                        <a href="{{ url('auth/registro') }}">Regístrate aquí</a>
                    </small>
                </div>
            </div>

            <!-- Imagen derecha -->
            <div class="col-md-6 p-0 d-none d-md-block">
                <img src="{{ asset('img/iniciosesion.jpg') }}"
                     class="login-image"
                     alt="Imagen de uñas">
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
                confirmButtonColor: '#d57492'
            })
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}',
                confirmButtonColor: '#d57492'
            })
        </script>
    @endif

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Credenciales incorrectas',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#d57492'
            })
        </script>
    @endif

</body>
@endsection
