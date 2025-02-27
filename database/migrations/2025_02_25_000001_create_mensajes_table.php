<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('id_mensaje');
            $table->foreignId('emisor_id')->constrained('users');
            $table->foreignId('receptor_id')->constrained('users');
            $table->text('contenido');
            $table->boolean('leido')->default(false);
            $table->timestamp('fecha_lectura')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensajes');
    }
}; 