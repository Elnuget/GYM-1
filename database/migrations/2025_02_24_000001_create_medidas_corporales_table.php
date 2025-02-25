<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medidas_corporales', function (Blueprint $table) {
            $table->id('id_medida');
            $table->foreignId('cliente_id')->constrained('clientes', 'id_cliente');
            $table->decimal('peso', 5, 2);
            $table->decimal('altura', 5, 2);
            $table->decimal('cuello', 5, 2)->nullable();
            $table->decimal('hombros', 5, 2)->nullable();
            $table->decimal('pecho', 5, 2)->nullable();
            $table->decimal('cintura', 5, 2)->nullable();
            $table->decimal('cadera', 5, 2)->nullable();
            $table->decimal('biceps', 5, 2)->nullable();
            $table->decimal('antebrazos', 5, 2)->nullable();
            $table->decimal('muslos', 5, 2)->nullable();
            $table->decimal('pantorrillas', 5, 2)->nullable();
            $table->date('fecha_medicion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medidas_corporales');
    }
}; 