<h2>Reporte de Ingresos</h2>
<p>Desde: {{ $inicio }} | Hasta: {{ $fin }}</p>

<h3>Ingresos Totales</h3>
<p>${{ number_format($ingresosTotales, 2) }}</p>

<h3>Ingresos por Empleado</h3>
@if($ingresosPorEmpleado->count())
<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Total Generado</th>
        </tr>
    </thead>
    <tbody>
    @foreach($ingresosPorEmpleado as $ingreso)
        <tr>
            <td>{{ $ingreso->empleado->nombre ?? 'Desconocido' }}</td>
            <td>${{ number_format($ingreso->total,2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<p>No hubo ingresos por empleado en este rango.</p>
@endif

<h3>Ingresos por Servicio</h3>
@if($ingresosPorServicio->count())
<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Servicio</th>
            <th>Total Generado</th>
        </tr>
    </thead>
    <tbody>
    @foreach($ingresosPorServicio as $ingreso)
        <tr>
            <td>{{ $ingreso->servicio->nombre ?? 'Desconocido' }}</td>
            <td>${{ number_format($ingreso->total,2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<p>No hubo ingresos por servicio en este rango.</p>
@endif
