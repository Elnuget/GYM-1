<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rutinas_cliente', function (Blueprint $table) {
            $table->id('id_rutina_cliente');
            $table->foreignId('cliente_id')->constrained('clientes', 'id_cliente')->onDelete('cascade');
            $table->foreignId('rutina_id')->constrained('rutinas_predefinidas', 'id_rutina')->onDelete('cascade');
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa');
            $table->integer('progreso')->default(0);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->text('notas_entrenador')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutinas_cliente');
    }
}; 