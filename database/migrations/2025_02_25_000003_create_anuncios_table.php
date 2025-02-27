<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anuncios', function (Blueprint $table) {
            $table->id('id_anuncio');
            $table->foreignId('gimnasio_id')->constrained('gimnasios', 'id_gimnasio');
            $table->string('titulo');
            $table->text('contenido');
            $table->string('imagen_url')->nullable();
            $table->boolean('importante')->default(false);
            $table->timestamp('fecha_publicacion');
            $table->timestamp('fecha_expiracion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anuncios');
    }
}; 