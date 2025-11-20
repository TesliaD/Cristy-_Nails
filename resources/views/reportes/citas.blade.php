<h2>Reporte de Citas</h2>
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



<h3>Citas Pendientes a Realizar</h3>

@if($citasArealizar->count())
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Cliente</th>
            <th>Servicio</th>
            <th>Encargado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($citasArealizar as $cita)
        <tr>
            <td>{{ $cita->fecha }}</td>
            <td>{{ $cita->hora }}</td>
            <td>{{ $cita->cliente->nombre }}</td>
            <td>{{ $cita->servicio->Nom_Servicio }}</td>
            <td>{{ $cita->empleado->usuario ?? 'Sin empleado asignado' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hay citas próximas a realizar en este rango de fechas.</p>
@endif


<h3>Citas Completadas</h3>

@if($citasAtendidas->count())
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Servicio</th>
            <th>Encargado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($citasAtendidas as $cita)
        <tr>
            <td>{{ $cita->fecha }}</td>
            <td>{{ $cita->cliente->nombre }}</td>
            <td>{{ $cita->servicio->Nom_Servicio }}</td>
            <td>{{ $cita->empleado->usuario ?? 'Sin empleado asignado' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hubo citas completadas en este rango de fechas.</p>
@endif


<h3>Citas Canceladas</h3>

@if($citasCanceladas->count())
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Servicio</th>
        </tr>
    </thead>
    <tbody>
        @foreach($citasCanceladas as $cita)
        <tr>
            <td>{{ $cita->fecha }}</td>
            <td>{{ $cita->cliente->nombre }}</td>
            <td>{{ $cita->servicio->Nom_Servicio }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No hubo citas canceladas en este rango de fechas.</p>
@endif
