<h2>Reporte de Clientes</h2>
<p>Desde: {{ $inicio }} | Hasta: {{ $fin }}</p>

<h3>Clientes Atendidos</h3>
@if($clientesAtendidos->count())
<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Total Citas</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($clientesAtendidos as $cliente)
        <tr>
            <td>{{ $cliente->nombre }}</td>
            <td>{{ $citasPorCliente->firstWhere('id', $cliente->id)->citas_count ?? 0 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<p>No hubo clientes atendidos en este rango de fechas.</p>
@endif

<h3>Clientes Nuevos</h3>
@if($clientesNuevos->count())
<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Fecha de Registro</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($clientesNuevos as $cliente)
        <tr>
            <td>{{ $cliente->nombre }}</td>
            <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<p>No hubo clientes nuevos en este rango de fechas.</p>
@endif
