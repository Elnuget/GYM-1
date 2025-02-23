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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gimnasio_id');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono')->nullable();
            $table->enum('genero', ['M', 'F', 'O'])->nullable();
            $table->string('ocupacion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gimnasio_id')->references('id_gimnasio')->on('gimnasios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
