<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nutricion', function (Blueprint $table) {
            $table->id('id_nutricion');
            $table->foreignId('user_id')->constrained();
            $table->text('informacion');
            $table->text('plan_dieta')->nullable();
            $table->date('fecha_asignacion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nutricion');
    }
}; 