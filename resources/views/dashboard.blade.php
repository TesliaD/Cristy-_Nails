@extends('app')

@section('title', 'Dashboard')

@section('content')
<h1>Nuestros Servicios</h1>

<div class="grid">
    <!-- Servicio 1 -->
    <div class="producto">
        <a href="{{ route('agendar', ['servicio' => 'manicura']) }}">
            <img class="producto__imagen" src="{{ asset('img/manicure.jpg') }}" alt="Manicura">
            <div class="producto__informacion">
                <p class="producto__nombre">💅 Manicura</p>
                <p class="producto__precio">$200 MXN</p>
            </div>
        </a>
    </div>

    <!-- Servicio 2 -->
    <div class="producto">
        <a href="{{ route('agendar', ['servicio' => 'pedicura']) }}">
            <img class="producto__imagen" src="{{ asset('img/pedicure.jpeg') }}" alt="Pedicura">
            <div class="producto__informacion">
                <p class="producto__nombre">🦶 Pedicura</p>
                <p class="producto__precio">$250 MXN</p>
            </div>
        </a>
    </div>

    <!-- Servicio 3 -->
    <div class="producto">
        <a href="{{ route('agendar', ['servicio' => 'diseno']) }}">
            <img class="producto__imagen" src="{{ asset('img/nailsdesign.jpg') }}" alt="Diseño de Uñas">
            <div class="producto__informacion">
                <p class="producto__nombre">🎨 Diseño de Uñas</p>
                <p class="producto__precio">$300 MXN</p>
            </div>
        </a>
    </div>

    <!-- Servicio 4 -->
    <div class="producto">
        <a href="{{ route('agendar', ['servicio' => 'otros']) }}">
            <img class="producto__imagen" src="{{ asset('img/otrosservicios.jpg') }}" alt="Otros Servicios">
            <div class="producto__informacion">
                <p class="producto__nombre">✨ Otros Servicios</p>
                <p class="producto__precio">Desde $150 MXN</p>
            </div>
        </a>
    </div>
</div>
@endsection
