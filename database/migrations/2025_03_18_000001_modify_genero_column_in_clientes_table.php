<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Modificamos la columna genero para que acepte valores más largos
            $table->string('genero', 20)->nullable()->change();
            
            // Aseguramos que exista la columna direccion
            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->string('direccion')->nullable()->after('ocupacion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Revertimos el cambio de genero
            $table->enum('genero', ['M', 'F', 'O'])->nullable()->change();
            
            // Eliminamos la columna direccion si la agregamos
            if (Schema::hasColumn('clientes', 'direccion')) {
                $table->dropColumn('direccion');
            }
        });
    }
}; 