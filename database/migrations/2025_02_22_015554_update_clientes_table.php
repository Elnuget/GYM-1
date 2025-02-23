<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Ya no necesitamos eliminar estas columnas porque no existen
            // $table->dropColumn(['nombre', 'email', 'telefono']);
            
            // Agregar columna user_id si no existe
            if (!Schema::hasColumn('clientes', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id_cliente');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (Schema::hasColumn('clientes', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            // Ya no necesitamos agregar estas columnas en el rollback
            // $table->string('nombre');
            // $table->string('email')->unique();
            // $table->string('telefono')->nullable();
        });
    }
} 