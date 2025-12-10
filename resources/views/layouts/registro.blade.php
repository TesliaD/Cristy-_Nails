@extends('layouts.app')

@section('title', 'Registro')

@section('content')

<link rel="stylesheet" href="{{ asset('css/registro.css') }}">

<div class="container d-flex justify-content-center align-items-center">
    <div class="row register-card w-100">

        <!-- FORMULARIO -->
        <div class="col-md-6 p-5">

            <div class="text-center mb-4">
                <img src="{{ asset('img/main-logo.png') }}" alt="Logo" width="110" class="rounded-circle shadow-sm">
                <h3 class="mt-3 titulo">Crear cuenta</h3>
                <p class="text-muted">Regístrate para continuar</p>
            </div>

            <!-- ALERTA ERRORES -->
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

            <!-- ALERTA ÉXITO -->
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
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control @error('usuario') is-invalid @enderror"
                           value="{{ old('usuario') }}" required>
                    @error('usuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono') }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion"
                           class="form-control @error('direccion') is-invalid @enderror"
                           value="{{ old('direccion') }}">
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                           value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Registrarse
                </button>
            </form>

            <div class="text-center mt-3">
                <small>¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión</a>
                </small>
            </div>
        </div>

        <!-- IMAGEN DERECHA -->
        <div class="col-md-6 p-0">
            <img src="{{ asset('img/iniciosesion.jpg') }}"
                 class="w-100 h-100"
                 style="object-fit: cover;">
        </div>

    </div>
</div>

@endsection
