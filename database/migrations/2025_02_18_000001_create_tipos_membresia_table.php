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
        Schema::create('tipos_membresia', function (Blueprint $table) {
            $table->id('id_tipo_membresia');
            $table->unsignedBigInteger('gimnasio_id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('duracion_dias');
            $table->enum('tipo', ['basica', 'estandar', 'premium']);
            $table->boolean('estado')->default(true);
            $table->timestamps();
            
            $table->foreign('gimnasio_id')->references('id_gimnasio')->on('gimnasios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_membresia');
    }
}; 