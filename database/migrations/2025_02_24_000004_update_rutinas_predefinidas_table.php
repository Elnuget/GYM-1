<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Primero manejamos el cambio de estado a activo
        Schema::table('rutinas_predefinidas', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('estado');
        });

        // Actualizamos los valores
        DB::statement("UPDATE rutinas_predefinidas SET activo = CASE WHEN estado = 'activo' THEN 1 ELSE 0 END");

        // Eliminamos la columna antigua
        Schema::table('rutinas_predefinidas', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        // Actualizamos el enum de objetivo para que coincida con objetivos_cliente
        DB::statement("ALTER TABLE rutinas_predefinidas MODIFY objetivo ENUM(
            'perdida_peso',
            'ganancia_muscular',
            'mantenimiento',
            'tonificacion',
            'resistencia',
            'flexibilidad'
        )");
    }

    public function down()
    {
        Schema::table('rutinas_predefinidas', function (Blueprint $table) {
            // Recreamos el campo estado
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
        });

        // Actualizamos los valores de estado basados en activo
        DB::statement("UPDATE rutinas_predefinidas SET estado = CASE WHEN activo = 1 THEN 'activo' ELSE 'inactivo' END");

        // Eliminamos la columna activo
        Schema::table('rutinas_predefinidas', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        // Revertimos el enum de objetivo a su estado original
        DB::statement("ALTER TABLE rutinas_predefinidas MODIFY objetivo ENUM(
            'fuerza',
            'resistencia',
            'tonificacion',
            'perdida_peso',
            'ganancia_muscular',
            'flexibilidad',
            'rehabilitacion',
            'mantenimiento'
        )");
    }
}; 