<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnboardingProgressTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('onboarding_progress')) {
            Schema::create('onboarding_progress', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cliente_id');
                $table->boolean('perfil_completado')->default(false);
                $table->boolean('medidas_iniciales')->default(false);
                $table->boolean('objetivos_definidos')->default(false);
                $table->boolean('tutorial_visto')->default(false);
                $table->timestamps();

                $table->foreign('cliente_id')
                      ->references('id_cliente')
                      ->on('clientes')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('onboarding_progress');
    }
} 