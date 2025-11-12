<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'servicio_id',
        'fecha',
        'hora',
        'estado',
        'notas',
    ];

    public function cliente() { return $this->belongsTo(Clientes::class, 'cliente_id'); }
    public function empleado() { return $this->belongsTo(Empleados::class, 'empleado_id'); }
    public function servicio() { return $this->belongsTo(Servicios::class, 'servicio_id'); }
    
}
