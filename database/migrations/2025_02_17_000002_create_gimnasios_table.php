<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gimnasios', function (Blueprint $table) {
            $table->id('id_gimnasio');
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefono', 20)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('logo')->nullable();
            $table->foreignId('dueno_id')->constrained('duenos_gimnasios', 'id_dueno')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gimnasios');
    }
}; 