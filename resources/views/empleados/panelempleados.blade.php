<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>Panel Empleados</div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="bi bi-person-fill-check"></i> Bienvenido, {{ Auth::user()->usuario ?? 'admin' }}</h1>
        </div>
</body>
</html>