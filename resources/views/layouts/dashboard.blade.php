@extends('layouts.app')

@section('title', 'Nuestros Servicios')

@section('content')




{{-- SECCIÓN DEL CARRUSEL DE SERVICIOS --}}
<section id="billboard" class="py-5 mt-3" style="background:#f1f1f0;">
    <div class="container">

        {{-- Encabezado --}}
        <div class="row justify-content-center">
            <h1 class="section-title text-center mt-4">Nuestros Servicios</h1>
            <div class="col-md-6 text-center">
                <p>Nuestro compromiso es realzar tu confianza y bienestar, brindándote servicios de belleza con pasión, precisión y creatividad.</p>
            </div>
        </div>

        {{-- CARRUSEL --}}
        <div class="row">
            <div class="swiper main-swiper py-4">

                <div class="swiper-wrapper d-flex border-animation-left">

                    {{-- RECORRER SERVICIOS DINÁMICAMENTE --}}
                    @forelse($servicios as $servicio)

                        <div class="swiper-slide">
                            <div class="banner-item image-zoom-effect">

                                {{-- Imagen --}}
                                <div class="image-holder">
                                    <a href="{{ route('agendar', ['servicio' => $servicio->Nom_Servicio]) }}">
                                        <img src="{{ $servicio->imagen ? asset('storage/' . $servicio->imagen) : asset('img/default-servicio.jpg') }}"
                                             class="img-fluid"
                                             alt="{{ $servicio->Nom_Servicio }}">
                                    </a>
                                </div>

                                {{-- Contenido --}}
                                <div class="banner-content py-4">
                                    <h5 class="element-title text-uppercase">
                                        <a class="item-anchor">{{ $servicio->Nom_Servicio }}</a>
                                    </h5>

                                    <p class="fw-semibold text-primary">
                                        ${{ number_format($servicio->Precio, 2) }} MXN
                                    </p>

                                    @if($servicio->Descripcion)
                                        <p>{{ $servicio->Descripcion }}</p>
                                    @endif

                                    <div class="btn-left">
                                        <a href="{{ route('agendar', ['servicio' => $servicio->Nom_Servicio]) }}"
                                           class="btn-link fs-6 text-uppercase item-anchor text-decoration-none">
                                            Agenda ahora
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @empty
                        <p class="text-center text-muted">No hay servicios registrados.</p>
                    @endforelse

                </div>

                {{-- PAGINACIÓN --}}
                <div class="swiper-pagination"></div>

                {{-- FLECHAS --}}
                <div class="icon-arrow icon-arrow-left" role="button" aria-label="Anterior">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div class="icon-arrow icon-arrow-right" role="button" aria-label="Siguiente">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                        <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

            </div>
        </div>

    </div>
</section>


@endsection
