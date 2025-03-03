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
        Schema::table('rutinas_predefinidas', function (Blueprint $table) {
            // Añadir el campo gimnasio_id si no existe
            if (!Schema::hasColumn('rutinas_predefinidas', 'gimnasio_id')) {
                $table->unsignedBigInteger('gimnasio_id')->after('id_rutina');
                
                // Crear la relación con la tabla gimnasios
                $table->foreign('gimnasio_id')
                      ->references('id_gimnasio')
                      ->on('gimnasios')
                      ->onDelete('cascade');
            }
            
            // Eliminar foreign key de user_id primero si existe
            if (Schema::hasColumn('rutinas_predefinidas', 'user_id')) {
                if (Schema::hasColumn('rutinas_predefinidas', 'id_entrenador')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn(['user_id', 'id_entrenador']);
                } else {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            }
            else if (Schema::hasColumn('rutinas_predefinidas', 'id_entrenador')) {
                $table->dropColumn('id_entrenador');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutinas_predefinidas', function (Blueprint $table) {
            // Eliminar la relación y el campo gimnasio_id si existe
            if (Schema::hasColumn('rutinas_predefinidas', 'gimnasio_id')) {
                $table->dropForeign(['gimnasio_id']);
                $table->dropColumn('gimnasio_id');
            }
            
            // Volver a añadir los campos eliminados
            if (!Schema::hasColumn('rutinas_predefinidas', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id_rutina');
                
                // Recrear la foreign key
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('rutinas_predefinidas', 'id_entrenador')) {
                $table->unsignedBigInteger('id_entrenador')->after('user_id');
            }
        });
    }
};
