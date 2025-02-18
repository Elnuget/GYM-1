<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rutinas_predefinidas', function (Blueprint $table) {
            $table->id('id_rutina');
            $table->foreignId('user_id')->constrained();
            $table->string('nombre_rutina');
            $table->text('descripcion');
            $table->string('objetivo');
            $table->enum('estado', ['activa', 'inactiva'])->default('activa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutinas_predefinidas');
    }
}; 