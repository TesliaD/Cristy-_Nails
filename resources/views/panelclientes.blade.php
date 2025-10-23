<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel Cliente</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- puedes añadir bootstrap si quieres -->
  <style>
    body{font-family: Arial, sans-serif; padding:20px}
    label{display:block; margin-top:8px}
    input, textarea, select{width:100%; padding:8px; margin-top:4px}
    .row {display:flex; gap:16px}
    .col{flex:1}
    .card{padding:16px; border:1px solid #ddd; border-radius:6px; margin-bottom:16px}
    .success{color:green}
  </style>
</head>
<body>
  <h1>Bienvenido, {{ $user->nombre ?? $user->Nombre ?? 'Cliente' }}</h1>

  <form method="POST" style="display:inline">
    @csrf
    <button type="submit">Cerrar sesión</button>
  </form>

  @if(session('success'))
    <p class="success">{{ session('success') }}</p>
  @endif

  <div class="card">
    <h3>Mi perfil</h3>
    <form method="POST">
      @csrf

      <label>Nombre</label>
      <input type="text" name="nombre">

      <label>Email</label>
      <input type="email" name="email" >

      <label>Teléfono</label>
      <input type="text" name="telefono" >

      <label>Dirección</label>
      <textarea name="direccion"></textarea>

      <label>Nueva contraseña (dejar en blanco para no cambiar)</label>
      <input type="password" name="password">

      <label>Confirmar contraseña</label>
      <input type="password" name="password_confirmation">

      <button type="submit" style="margin-top:10px">Actualizar perfil</button>
    </form>
  </div>

  <div class="card">
    <h3>Agendar cita</h3>
    <p>Ir a la página de agendamiento para seleccionar servicio, fecha y hora.</p>
    <p><a href="{{ route('agendar') }}">Ir a Agendar</a></p>
  </div>

  <div class="card">
    <h3>Mis próximas citas</h3>

  </div>

</body>
</html>
