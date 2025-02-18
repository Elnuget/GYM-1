<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->foreignId('user_id')->constrained();
            $table->date('fecha_asistencia');
            $table->time('hora_ingreso');
            $table->time('hora_salida')->nullable();
            $table->enum('estado', ['presente', 'ausente'])->default('presente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
}; 