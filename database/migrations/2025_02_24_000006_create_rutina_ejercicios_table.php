<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rutina_ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rutina_id')->constrained('rutinas_predefinidas', 'id_rutina')->onDelete('cascade');
            $table->foreignId('ejercicio_id')->constrained('ejercicios', 'id_ejercicio')->onDelete('cascade');
            $table->integer('dia');
            $table->integer('orden');
            $table->integer('series');
            $table->integer('repeticiones');
            $table->string('peso_sugerido')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutina_ejercicios');
    }
}; 