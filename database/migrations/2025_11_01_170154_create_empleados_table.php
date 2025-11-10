<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
                  $table->id('empleado_id'); 
            $table->foreignId('usuario_id') 
                ->constrained('usuarios') // Esto crea una clave foránea que apunta a la tabla 'usuarios'
                ->onDelete('cascade'); // Si el usuario es eliminado, el empleado se elimina también
            $table->string('E_nombre')->nullable();
            $table->string('E_telefono')->nullable();
            $table->string('E_direccion')->nullable();
            $table->date('E_fecha_nacimiento')->nullable();
            $table->timestamps(); // Esto creará 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
