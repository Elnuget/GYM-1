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
        Schema::create('gimnasios', function (Blueprint $table) {
            $table->id('id_gimnasio');
            $table->foreignId('dueno_id')->constrained('duenos_gimnasios', 'id_dueno')->onDelete('cascade');
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gimnasios');
    }
};
