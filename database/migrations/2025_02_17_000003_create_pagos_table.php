<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('id_membresia')->constrained('membresias', 'id_membresia');
            $table->foreignId('id_usuario')->constrained('users', 'id');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->enum('estado_pago', ['pagado', 'pendiente', 'parcial']);
            $table->foreignId('id_metodo_pago')->constrained('metodos_pago', 'id_metodo_pago');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}; 