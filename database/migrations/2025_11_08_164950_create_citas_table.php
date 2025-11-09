<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            // Relación con tabla de cliente
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');

            //Relación con tabla de empleado 
            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->onDelete('set null')
                ->nullable(); // por si la cita aún no tiene empleado asignado

            // Relación con tabla de servicio
            $table->foreignId('servicio_id')
                ->constrained('servicios')
                ->onDelete('cascade');

            // Tabla de citas cita
            $table->date('fecha');           // Fecha de la cita
            $table->time('hora');            // Hora de la cita
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])
                  ->default('pendiente');
            $table->text('notas')->nullable(); // Notas opcionales del cliente

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
};
