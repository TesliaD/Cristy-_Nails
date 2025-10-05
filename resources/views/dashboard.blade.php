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
<body>
    <h2>Nuestra Historia</h2>
    <p class="center">
        Cristy Nails and Beauty nació con la idea de ofrecer un espacio donde cada persona pueda relajarse, 
        cuidarse y expresar su estilo personal. Con más de 5 años de experiencia, nuestro equipo se 
        especializa en realzar la belleza de tus manos y pies con técnicas innovadoras y productos de calidad.
    </p>
    
    <h2>Misión</h2>
    <p class="center">
        Brindar un servicio de cuidado estético profesional, con calidez humana y atención personalizada, 
        para que cada cliente se sienta único y especial.
    </p>

    <h2>Visión</h2>
    <p class="center">
        Ser el salón de uñas líder en la región, reconocido por la creatividad en diseños y el compromiso 
        con la satisfacción de nuestros clientes.
    </p>

    <h2>Nuestros Valores</h2>
    <ul class="center">
        <li>✨ Calidad en cada servicio</li>
        <li>💖 Atención personalizada</li>
        <li>🌱 Uso de productos seguros y de confianza</li>
        <li>🎨 Creatividad e innovación constante</li>
    </ul>

    <h2>Nuestro Local</h2>
    <div>Insertar Fotos</div>

</body>
@endsection
