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
        // Primero eliminamos la tabla si existe
        Schema::dropIfExists('tipos_membresia');
        
        // Luego la recreamos con la estructura correcta
        Schema::create('tipos_membresia', function (Blueprint $table) {
            $table->id('id_tipo_membresia');
            $table->unsignedBigInteger('gimnasio_id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);

            // Dependiendo de tu lógica de negocio, podrías continuar usando duracion_dias
            // para los planes mensual o anual (por ejemplo, 30 días para mensual y 365 para anual).
            // O bien podrías eliminar este campo si vas a manejarlo de otra manera.
            $table->integer('duracion_dias')->nullable();

            // Cambiamos el tipo a enum con opciones 'mensual', 'anual' y 'visitas'
            $table->enum('tipo', ['mensual', 'anual', 'visitas']);

            // Nuevo campo para almacenar el número de visitas en caso de plan por visitas
            $table->integer('numero_visitas')->nullable();

            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('gimnasio_id')
                  ->references('id_gimnasio')
                  ->on('gimnasios')
                  ->onDelete('cascade');
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
