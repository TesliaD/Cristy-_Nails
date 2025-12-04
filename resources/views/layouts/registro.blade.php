@extends('layouts.app')

@section('title', 'Registro')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Cristy Nails and Beauty</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tus estilos -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="login-body d-flex align-items-center vh-100">
    <div class="container">
        <div class="row shadow-lg rounded overflow-hidden">

            <!-- Columna izquierda (formulario) -->
            <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center">

                <div class="text-center mb-4">
                    <img src="{{ asset('img/nailslogo.jpg') }}" alt="Logo" width="100">
                    <h4 class="mt-3">Crear cuenta</h4>
                    <p class="text-muted">Regístrate para continuar</p>
                </div>

                <!-- ALERTA SWEETALERT DE ERRORES -->
                @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Revisa los campos',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonColor: '#d33'
                    });
                </script>
                @endif

                <!-- ALERTA SWEETALERT DE ÉXITO -->
                @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Registro exitoso',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6'
                    });
                </script>
                @endif

                <form method="POST" action="{{ route('registro.guardar') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" 
                               class="form-control @error('usuario') is-invalid @enderror" 
                               name="usuario" 
                               id="usuario" 
                               value="{{ old('usuario') }}" 
                               required>
                        
                        @error('usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" id="email" 
                               value="{{ old('email') }}" 
                               required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" 
                               class="form-control @error('nombre') is-invalid @enderror"
                               name="nombre" id="nombre" 
                               value="{{ old('nombre') }}">

                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" 
                               class="form-control @error('telefono') is-invalid @enderror"
                               name="telefono" id="telefono" 
                               value="{{ old('telefono') }}">

                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" 
                               class="form-control @error('direccion') is-invalid @enderror"
                               name="direccion" id="direccion" 
                               value="{{ old('direccion') }}">

                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                        <input type="date" 
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                               name="fecha_nacimiento" id="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento') }}">

                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" id="password"
                               required>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" 
                               class="form-control"
                               name="password_confirmation" 
                               id="password_confirmation"
                               required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <small>¿Ya tienes cuenta? 
                        <a href="{{ route('login') }}">Inicia sesión</a>
                    </small>
                </div>
            </div>

            <!-- Columna derecha (imagen) -->
            <div class="col-md-6 p-0">
                <img src="{{ asset('img/iniciosesion.jpg') }}" alt="Imagen" class="w-100 h-100" style="object-fit: cover;">
            </div>

        </div>
    </div>
</body>
</html>

@endsection
