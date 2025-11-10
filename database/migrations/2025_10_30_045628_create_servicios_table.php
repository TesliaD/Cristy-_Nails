<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id(); // ✅ mejor usar id normal
            $table->string('Nom_Servicio', 100);
            $table->text('Descripcion')->nullable();
            $table->decimal('Precio', 8, 2);
            $table->integer('Duracion')->nullable(); 
            $table->boolean('Activo')->default(true);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicios');
    }
};

