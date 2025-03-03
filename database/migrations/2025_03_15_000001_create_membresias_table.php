<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('membresias', function (Blueprint $table) {
            $table->id('id_membresia');
            $table->unsignedBigInteger('id_usuario');
            // Nuevo campo que hace referencia a "tipos_membresia"
            $table->unsignedBigInteger('id_tipo_membresia');

            $table->decimal('precio_total', 10, 2);
            $table->decimal('saldo_pendiente', 10, 2)->default(0);
            $table->date('fecha_compra');
            $table->date('fecha_vencimiento')->nullable();
            $table->integer('visitas_permitidas')->nullable();
            $table->integer('visitas_restantes')->nullable();
            $table->boolean('renovacion')->default(false);
            $table->timestamps();

            // Relación con la tabla users (ajusta si tu PK se llama distinto)
            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Relación con la tabla tipos_membresia
            $table->foreign('id_tipo_membresia')
                  ->references('id_tipo_membresia')
                  ->on('tipos_membresia')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('membresias');
    }
};
