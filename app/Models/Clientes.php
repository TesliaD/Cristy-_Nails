<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'telefono',
        'direccion',
        'fecha_nacimiento',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
