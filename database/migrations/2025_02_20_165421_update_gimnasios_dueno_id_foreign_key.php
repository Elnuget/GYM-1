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
        // Primero eliminamos las tablas que dependen de gimnasios
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('pagos_gimnasios');
        
        // Luego modificamos la tabla gimnasios
        Schema::table('gimnasios', function (Blueprint $table) {
            $table->dropForeign(['dueno_id']);
            $table->dropColumn('dueno_id');
        });

        Schema::table('gimnasios', function (Blueprint $table) {
            $table->foreignId('dueno_id')->after('id_gimnasio')->constrained('users')->onDelete('cascade');
        });

        // Recreamos las tablas dependientes con la estructura correcta
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

        Schema::create('pagos_gimnasios', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('dueno_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->enum('estado', ['pagado', 'pendiente'])->default('pendiente');
            $table->enum('metodo_pago', ['tarjeta_credito', 'efectivo', 'transferencia_bancaria']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las tablas dependientes
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('pagos_gimnasios');

        // Revertimos los cambios en gimnasios
        Schema::table('gimnasios', function (Blueprint $table) {
            $table->dropForeign(['dueno_id']);
            $table->dropColumn('dueno_id');
        });

        Schema::table('gimnasios', function (Blueprint $table) {
            $table->foreignId('dueno_id')->after('id_gimnasio')->constrained('duenos_gimnasios', 'id_dueno')->onDelete('cascade');
        });

        // Recreamos las tablas dependientes con la estructura original
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

        Schema::create('pagos_gimnasios', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('dueno_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->enum('estado', ['pagado', 'pendiente'])->default('pendiente');
            $table->enum('metodo_pago', ['tarjeta_credito', 'efectivo', 'transferencia_bancaria']);
            $table->timestamps();
        });
    }
}; 