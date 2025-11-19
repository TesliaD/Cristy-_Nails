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
        Schema::create('reportes', function (Blueprint $table) {
        $table->id();

        // Tipo de reporte - clientes, citas, ingresos, servicios
        $table->string('tipo');

        // Fechas que ABARCA el reporte
        $table->date('fecha_inicio');
        $table->date('fecha_fin');

        // Ruta del archivo PDF almacenado
        $table->string('ruta_pdf');

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};
