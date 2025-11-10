<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // ✅ Importación necesaria

class Clientes extends Model
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

    // 🔹 Relación con la tabla usuarios
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
