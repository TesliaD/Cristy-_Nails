<h2>Reporte de Clientes</h2>
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



<h3>Clientes Atendidos</h3>

@if($clientesAtendidos->count())
<table>
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
<table>
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
