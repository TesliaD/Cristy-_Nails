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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('Nom_Servicio', 100);
            $table->text('Descripcion')->nullable();
            $table->decimal('Precio', 8, 2);
            $table->integer('Duracion')->nullable(); // minutos
            $table->boolean('Activo')->default(true); // para activar/desactivar en la web
            $table->string('imagen')->nullable();
            $table->timestamps(); //Crea de manera automatica las tablas de fecha de create y update

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios');
    }
};
