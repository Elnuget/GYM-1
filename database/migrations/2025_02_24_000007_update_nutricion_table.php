<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nutricion', function (Blueprint $table) {
            // Renombramos la columna user_id a cliente_id y actualizamos la referencia
            $table->renameColumn('user_id', 'cliente_id');
            $table->foreign('cliente_id')->references('id_cliente')->on('clientes')->onDelete('cascade');
            
            // Agregamos los nuevos campos
            $table->string('nombre_plan')->after('cliente_id');
            $table->enum('estado', ['activo', 'completado', 'cancelado'])->default('activo')->after('plan_dieta');
            $table->integer('calorias_diarias')->after('estado');
            $table->integer('proteinas')->comment('gramos por día')->after('calorias_diarias');
            $table->integer('carbohidratos')->comment('gramos por día')->after('proteinas');
            $table->integer('grasas')->comment('gramos por día')->after('carbohidratos');
            $table->text('recomendaciones')->nullable()->after('grasas');
            $table->date('fecha_fin')->nullable()->after('fecha_asignacion');
        });

        Schema::create('comidas_nutricion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutricion_id')->constrained('nutricion', 'id_nutricion')->onDelete('cascade');
            $table->string('nombre_comida');
            $table->time('hora_sugerida');
            $table->integer('calorias');
            $table->text('descripcion');
            $table->text('alimentos')->nullable();
            $table->text('instrucciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comidas_nutricion');
        
        Schema::table('nutricion', function (Blueprint $table) {
            $table->renameColumn('cliente_id', 'user_id');
            $table->dropForeign(['cliente_id']);
            $table->dropColumn([
                'nombre_plan',
                'estado',
                'calorias_diarias',
                'proteinas',
                'carbohidratos',
                'grasas',
                'recomendaciones',
                'fecha_fin'
            ]);
        });
    }
}; 