@extends('layouts.app')

@section('title', 'Nuestros Servicios')

@section('content')
<h1 class="text-center mb-4">Nuestros Servicios</h1>

<div class="grid">
    @forelse($servicios as $servicio)
        <div class="producto">
            <a href="{{ route('agendar', ['servicio' => $servicio->Nom_Servicio]) }}">
                <img class="producto__imagen"
                     src="{{ $servicio->imagen ? asset('storage/' . $servicio->imagen) : asset('img/default-servicio.jpg') }}"
                     alt="{{ $servicio->Nom_Servicio }}">

                <div class="producto__informacion">
                    <p class="producto__nombre">💅 {{ $servicio->Nom_Servicio }}</p>
                    <p class="producto__precio">${{ number_format($servicio->Precio, 2) }} MXN</p>

                    @if($servicio->Descripcion)
                        <p class="producto__descripcion">{{ $servicio->Descripcion }}</p>
                    @endif
                </div>
            </a>
        </div>
    @empty
        <p class="text-center">No hay servicios disponibles por el momento.</p>
    @endforelse
</div>
@endsection
