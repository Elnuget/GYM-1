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
            $table->unsignedBigInteger('user_id');
            $table->string('nombre_rutina');
            $table->text('descripcion');
            $table->enum('objetivo', [
                'fuerza',
                'resistencia',
                'tonificacion',
                'perdida_peso',
                'ganancia_muscular',
                'flexibilidad',
                'rehabilitacion',
                'mantenimiento'
            ]);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->date('fecha_creacion');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutinas_predefinidas');
    }
}; 