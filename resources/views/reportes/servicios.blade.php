<h2>Reporte de Servicios</h2>
<p>Desde: {{ $inicio }} | Hasta: {{ $fin }}</p>

<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
}
th, td {
    border: 1px solid #000;
    padding: 6px 8px;
    text-align: left;
}
th {
    background: #f0f0f0;
    font-weight: bold;
}
</style>

<h3>Servicios Realizados</h3>
@if($serviciosRealizados->count())
<table>
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
            <td>{{ $cita->servicio->Nom_Servicio ?? '-' }}</td>
            <td>{{ $cita->empleado->usuario ?? 'Sin empleado asignado' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No se realizaron servicios en este rango de fechas.</p>
@endif

<h3>Servicios Más Hechos</h3>
@if($serviciosMasHechos->count())
<table>
    <thead>
        <tr>
            <th>Servicio</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($serviciosMasHechos as $servicio)
        <tr>
            <td>{{ $servicio->servicio->Nom_Servicio ?? 'Desconocido' }}</td>
            <td>{{ $servicio->total }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hay servicios más hechos.</p>
@endif

<h3>Servicios Menos Hechos</h3>
@if($serviciosMenosHechos->count())
<table>
    <thead>
        <tr>
            <th>Servicio</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($serviciosMenosHechos as $servicio)
        <tr>
            <td>{{ $servicio->Nom_Servicio ?? 'Desconocido' }}</td>
            <td>{{ $servicio->total }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hay servicios menos hechos.</p>
@endif
