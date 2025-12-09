@extends('layouts.app')
@section('title', 'Nuestros Servicios')

@section('content')

<section class="container">

    <div class="text-center mb-4">
        <h1 class="fw-bold" style="color: var(--rosa-oscuro);">Nuestros Servicios</h1>
        <p class="text-muted">Realzamos tu belleza con pasión y precisión.</p>
    </div>

    <!-- CARRUSEL SWIPER -->
    <div class="swiper main-swiper">

        <div class="swiper-wrapper">

            @foreach($servicios as $servicio)
                <div class="swiper-slide p-0">

                    <div class="service-card">

                        <img src="{{ $servicio->imagen ? asset('storage/'.$servicio->imagen) : asset('img/default-servicio.jpg') }}">

                        <div class="p-3">
                            <h5 class="service-title">{{ $servicio->Nom_Servicio }}</h5>

                            <p class="fw-bold text-dark">
                                ${{ number_format($servicio->Precio, 2) }} MXN
                            </p>

                            @if($servicio->Descripcion)
                                <p class="text-muted small">{{ $servicio->Descripcion }}</p>
                            @endif

                            <a href="{{ route('agendar', ['servicio'=>$servicio->Nom_Servicio]) }}" 
                               class="btn btn-primary w-100 mt-2">
                               Agendar ahora
                            </a>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        <!-- Indicadores -->
        <div class="swiper-pagination"></div>

        <!-- FLECHAS -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

    </div>

</section>

@endsection

@push('scripts')
<script>
new Swiper(".main-swiper", {
    slidesPerView: 1,
    loop: true,
    spaceBetween: 20,
    pagination: {
        el: ".swiper-pagination",
        clickable: true
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
    },
    breakpoints: {
        768: { slidesPerView: 2 },
        992: { slidesPerView: 3 }
    }
});
</script>
@endpush
