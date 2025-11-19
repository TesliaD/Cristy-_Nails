<h2>Reporte de Citas</h2>
<p>Desde: {{ $inicio }} | Hasta: {{ $fin }}</p>

<h3>Citas Completadas</h3>
<ul>
    @forelse($citasAtendidas as $cita)
        <li>{{ $cita->fecha }} - {{ $cita->cliente->nombre }} - {{ $cita->servicio->nombre }}</li>
    @empty
        <li>No hubo citas completadas en este rango de fechas.</li>
    @endforelse
</ul>

<h3>Citas Canceladas</h3>
<ul>
    @forelse($citasCanceladas as $cita)
        <li>{{ $cita->fecha }} - {{ $cita->cliente->nombre }} - {{ $cita->servicio->nombre }}</li>
    @empty
        <li>No hubo citas canceladas en este rango de fechas.</li>
    @endforelse
</ul>
