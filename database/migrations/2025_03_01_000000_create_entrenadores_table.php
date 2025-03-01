<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrenadores', function (Blueprint $table) {
            $table->id('id_entrenador');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gimnasio_id');
            $table->string('especialidad')->nullable();
            $table->string('certificaciones')->nullable();
            $table->string('telefono');
            $table->text('experiencia')->nullable();
            $table->string('horario_disponibilidad')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gimnasio_id')->references('id_gimnasio')->on('gimnasios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenadores');
    }
}; 