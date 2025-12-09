@extends('layouts.app')

@section('title', 'Nuestra Historia')

@section('content')

<style>
    body {
        background-color: #f8f9fa;
    }

    .section-title {
        text-align: center;
        font-weight: 700;
        margin-bottom: 20px;
        margin-top: 40px;
        font-size: 2rem;
        color: #d63384;
    }

    .text-content {
        max-width: 900px;
        margin: auto;
        text-align: center;
        font-size: 1.1rem;
        line-height: 1.7;
        color: #444;
    }

    .values-list {
        max-width: 900px;
        margin: auto;
        font-size: 1.1rem;
        color: #444;
        list-style: none;
        padding-left: 0;
    }

    .values-list li {
        background: #fff;
        padding: 12px;
        margin: 10px 0;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .photo-box {
        width: 100%;
        height: 350px;
        background: #e9ecef;
        border-radius: 15px;
        margin-top: 20px;
        margin-bottom: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #777;
        font-size: 1.2rem;
        border: 2px dashed #bbb;
    }

    /* Animación suave */
    .fade-in {
        opacity: 0;
        animation: fadeIn 0.8s forwards;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }
</style>

<div class="container fade-in">

    <!-- HISTORIA -->
    <h2 class="section-title">Nuestra Historia</h2>
    <p class="text-content">
        Cristy Nails and Beauty nació con la idea de ofrecer un espacio donde cada persona pueda relajarse, 
        cuidarse y expresar su estilo personal. Con más de 5 años de experiencia, nuestro equipo se 
        especializa en realzar la belleza de tus manos y pies con técnicas innovadoras y productos de calidad.
    </p>

    <!-- MISIÓN -->
    <h2 class="section-title">Misión</h2>
    <p class="text-content">
        Brindar un servicio de cuidado estético profesional, con calidez humana y atención personalizada, 
        para que cada cliente se sienta único y especial.
    </p>

    <!-- VISIÓN -->
    <h2 class="section-title">Visión</h2>
    <p class="text-content">
        Ser el salón de uñas líder en la región, reconocido por la creatividad en diseños y el compromiso 
        con la satisfacción de nuestros clientes.
    </p>

    <!-- VALORES -->
    <h2 class="section-title">Nuestros Valores</h2>
    <ul class="values-list">
        <li>✨ Calidad en cada servicio</li>
        <li>💖 Atención personalizada</li>
        <li>🌱 Uso de productos seguros y de confianza</li>
        <li>🎨 Creatividad e innovación constante</li>
    </ul>

    <!-- FOTOS -->
    <h2 class="section-title">Nuestro Local</h2>

    <div class="photo-box">
        Insertar Fotos Aquí
    </div>

</div>

@endsection
