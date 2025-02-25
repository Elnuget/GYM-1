<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Renombrar columnas existentes
            $table->renameColumn('user_id', 'cliente_id');
            $table->renameColumn('fecha_asistencia', 'fecha');
            $table->renameColumn('hora_ingreso', 'hora_entrada');
            
            // Agregar nuevas columnas
            $table->integer('duracion_minutos')->nullable()->after('hora_salida');
            $table->text('notas')->nullable()->after('estado');
            
            // Modificar la columna estado
            $table->string('estado')->default('activa')->change();
            
            // Actualizar la referencia foreign key
            $table->foreign('cliente_id')->references('id_cliente')->on('clientes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Eliminar foreign key
            $table->dropForeign(['cliente_id']);
            
            // Revertir columnas renombradas
            $table->renameColumn('cliente_id', 'user_id');
            $table->renameColumn('fecha', 'fecha_asistencia');
            $table->renameColumn('hora_entrada', 'hora_ingreso');
            
            // Eliminar columnas nuevas
            $table->dropColumn(['duracion_minutos', 'notas']);
            
            // Revertir la columna estado
            $table->enum('estado', ['presente', 'ausente'])->default('presente')->change();
        });
    }
}; 