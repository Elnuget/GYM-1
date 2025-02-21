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
        Schema::create('duenos_gimnasios', function (Blueprint $table) {
            $table->id('id_dueno');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre_comercial')->nullable();
            $table->string('telefono_gimnasio')->nullable();
            $table->string('direccion_gimnasio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duenos_gimnasios');
    }
}; 