<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('rol', ['admin', 'cliente', 'empleado'])->default('cliente');
            $table->rememberToken();
            $table->timestamps(); // Esto es importante si usas 'created_at' y 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
