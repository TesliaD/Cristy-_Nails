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

            // Cliente
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');

            // Empleado (usuarios con rol 'empleado')
            $table->foreignId('empleado_id')
                ->nullable()
                ->constrained('usuarios')
                ->onDelete('set null');

            // Servicio
            $table->foreignId('servicio_id')
                ->constrained('servicios')
                ->onDelete('cascade');

            // Datos de cita
            $table->date('fecha');
            $table->time('hora');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])
                  ->default('pendiente');
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
};
