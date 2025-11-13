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
        'notas', // ✅ agregado
    ];

<<<<<<< HEAD
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id');
    }

    public function empleado()
    {
        return $this->belongsTo(User::class, 'empleado_id'); 
    }

    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }
=======
    public function cliente() { return $this->belongsTo(Clientes::class, 'cliente_id'); }
    public function empleado() { return $this->belongsTo(Empleados::class, 'empleado_id'); }
    public function servicio() { return $this->belongsTo(Servicios::class, 'servicio_id'); }
    
>>>>>>> ef256ccf822ef34f1c79936ea931bf0a9759fa59
}
