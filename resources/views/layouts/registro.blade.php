@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Cristy Nails and Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(path: 'css/styles.css') }}">
</head>
<body class="login-body d-flex align-items-center vh-100">
    <div class="container">
        <div class="row shadow-lg rounded overflow-hidden">

            <!-- Columna izquierda (formulario de registro) -->
            <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center">
                <div class="text-center mb-4">
                    <img src="{{ asset(path: 'img/nailslogo.jpg') }}" alt="Logo" width="100">
                    <h4 class="mt-3">Crear cuenta</h4>
                    <p class="text-muted">Regístrate para continuar</p>
                </div>

                <!-- Mostrar errores -->
                @if($errors->any())
                    <div class="alert alert-danger text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

        <form method="POST" action="{{ route('registro.guardar') }}">
            @csrf
    
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" id="usuario" value="{{ old('usuario') }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre') }}">
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" id="telefono" value="{{ old('telefono') }}">
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" id="direccion" value="{{ old('direccion') }}">
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>

                <div class="text-center mt-3">
                    <small>¿Ya tienes cuenta? <a href="{{ route(name: 'login') }}">Inicia sesión</a></small>
                </div>
            </div>

            <!-- Columna derecha (imagen) -->
            <div class="col-md-6 p-0">
                <img src="{{ asset(path: 'img/iniciosesion.jpg') }}" alt="Imagen" class="w-100 h-100" style="object-fit: cover;">
            </div>

        </div>
    </div>
</body>
</html>
@endsection