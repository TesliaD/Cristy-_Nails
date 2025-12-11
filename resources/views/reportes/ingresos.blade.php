<h2>Reporte de Ingresos</h2>
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

<h3 style="margin-bottom:5px; font-weight:600;">
    Ingresos Totales
</h3>
<p style="
    font-size: 26px;
    font-weight: bold;
    color: #000;
    margin: 0;
">
    ${{ number_format($ingresosTotales, 2) }}
</p>

<h4 style="margin-bottom:5px">Ingresos Extras<br>(Agregados antes de finalizar una cita)</h4>
<p style="
    font-size: 20px;
    font-weight: bold;
    color: #000;
    margin: 0;
">
    ${{ number_format($costoExtraTotal, 2) }}
</p>

<h3>Ingresos por Empleado</h3>
@if($ingresosPorEmpleado->count())
<table>
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Total Generado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ingresosPorEmpleado as $ingreso)
        <tr>
            <td>{{ $ingreso->empleado->usuario ?? 'Sin empleado asignado' }}</td>
            <td>${{ number_format($ingreso->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hubo ingresos por empleado en este rango.</p>
@endif

<h3>Ingresos por Servicio</h3>
@if($ingresosPorServicio->count())
<table>
    <thead>
        <tr>
            <th>Servicio</th>
            <th>Total Generado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ingresosPorServicio as $ingreso)
        <tr>
            <td>{{ $ingreso->servicio->Nom_Servicio ?? 'Desconocido' }}</td>
            <td>${{ number_format($ingreso->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hubo ingresos por servicio en este rango.</p>
@endif
