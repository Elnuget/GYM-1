<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asignacion_rutinas', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('id_rutina')
                  ->constrained('rutinas_predefinidas', 'id_rutina')
                  ->onDelete('restrict');
            $table->date('fecha_asignacion');
            $table->enum('dia_semana', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])
                  ->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignacion_rutinas');
    }
}; 