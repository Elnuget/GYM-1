<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('objetivos_cliente', function (Blueprint $table) {
            $table->id('id_objetivo');
            $table->foreignId('cliente_id')->constrained('clientes', 'id_cliente');
            $table->enum('objetivo_principal', [
                'perdida_peso',
                'ganancia_muscular',
                'mantenimiento',
                'tonificacion',
                'resistencia',
                'flexibilidad'
            ]);
            $table->enum('nivel_experiencia', ['principiante', 'intermedio', 'avanzado']);
            $table->string('dias_entrenamiento');
            $table->text('condiciones_medicas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('objetivos_cliente');
    }
}; 