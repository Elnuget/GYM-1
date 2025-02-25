<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id('id_ejercicio');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('imagen_url')->nullable();
            $table->string('video_url')->nullable();
            $table->text('instrucciones')->nullable();
            $table->string('grupo_muscular');
            $table->string('equipamiento_necesario')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ejercicios');
    }
}; 