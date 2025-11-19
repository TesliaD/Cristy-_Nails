<h2>Reporte de Servicios</h2>
<p>Desde: {{ $inicio }} | Hasta: {{ $fin }}</p>

<h3>Servicios Realizados</h3>
@if($serviciosRealizados->count())
<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Servicio</th>
            <th>Empleado</th>
        </tr>
    </thead>
    <tbody>
    @foreach($serviciosRealizados as $cita)
        <tr>
            <td>{{ $cita->fecha }}</td>
            <td>{{ $cita->servicio->nombre ?? '-' }}</td>
            <td>{{ $cita->empleado->nombre ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<p>No se realizaron servicios en este rango de fechas.</p>
@endif

<h3>Servicios Más Hechos</h3>
@if($serviciosMasHechos->count())
<ul>
@foreach($serviciosMasHechos as $servicio)
    <li>{{ $servicio->servicio->nombre ?? 'Desconocido' }} - {{ $servicio->total }} veces</li>
@endforeach
</ul>
@else
<p>No hay servicios más hechos.</p>
@endif

<h3>Servicios Menos Hechos</h3>
@if($serviciosMenosHechos->count())
<ul>
@foreach($serviciosMenosHechos as $servicio)
    <li>{{ $servicio->servicio->nombre ?? 'Desconocido' }} - {{ $servicio->total }} veces</li>
@endforeach
</ul>
@else
<p>No hay servicios menos hechos.</p>
@endif
