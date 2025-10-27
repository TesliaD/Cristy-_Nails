<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Nombre de la tabla en la base de datos
    protected $table = 'usuarios';

    // Si no desactivas los timestamps, Laravel manejará 'created_at' y 'updated_at'
    // public $timestamps = false; // No es necesario si usas 'created_at' y 'updated_at'

    protected $fillable = [
        'usuario',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // Esto es para asegurar que se convierta en objeto de fecha.
    ];

     public function cliente()
    {
        return $this->hasOne(clientes::class, 'usuario_id');
    }
}
