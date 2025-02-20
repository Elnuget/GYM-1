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
        Schema::create('pagos_gimnasios', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('dueno_id')->constrained('duenos_gimnasios', 'id_dueno')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->enum('estado', ['pagado', 'pendiente'])->default('pendiente');
            $table->enum('metodo_pago', ['tarjeta_credito', 'efectivo', 'transferencia_bancaria']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_gimnasios');
    }
};
