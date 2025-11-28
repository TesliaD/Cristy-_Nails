@extends('layouts.app')

@section('title', 'Nuestros Servicios')

@section('content')

<h1 class="text-center fw-bold mb-5" style="font-size: 2.8rem;">
    Nuestros Servicios
</h1>

<div class="row justify-content-center g-4">
    @forelse($servicios as $servicio)
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex">
            <div class="card shadow-sm border-0 rounded-4 w-100" style="overflow: hidden;">
                
                <!-- Imagen -->
                <a href="{{ route('agendar', ['servicio' => $servicio->Nom_Servicio]) }}">
                    <img src="{{ $servicio->imagen ? asset('storage/' . $servicio->imagen) : asset('img/default-servicio.jpg') }}"
                         class="card-img-top"
                         alt="{{ $servicio->Nom_Servicio }}"
                         style="height: 220px; object-fit: cover;">
                </a>

                <div class="card-body text-center">

                    <h5 class="card-title fw-bold">
                        💅 {{ $servicio->Nom_Servicio }}
                    </h5>

                    <p class="text-primary fw-semibold" style="font-size: 1.1rem;">
                        ${{ number_format($servicio->Precio, 2) }} MXN
                    </p>

                    @if($servicio->Descripcion)
                        <p class="text-muted small">{{ $servicio->Descripcion }}</p>
                    @endif

                    <a href="{{ route('agendar', ['servicio' => $servicio->Nom_Servicio]) }}"
                       class="btn btn-pink mt-2 px-4 py-2 rounded-pill">
                        Agendar
                    </a>

                </div>
            </div>
        </div>
    @empty
        <p class="text-center">No hay servicios disponibles por el momento.</p>
    @endforelse
</div>

@endsection
