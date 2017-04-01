<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cuadrante_id')->unsigned();
            $table->integer('empleado_id')->unsigned();
            $table->integer('ausencia_id')->unsigned()->nullable();
            $table->date('fecha');
            $table->tinyInteger('dia')->unsigned();
            $table->enum('situacion',['V','FT','B','AJ','AN','L','VT','F','BP','PR'])->nullable();
            $table->time('entrada1')->nullable();
            $table->time('salida1')->nullable();
            $table->time('entrada2')->nullable();
            $table->time('salida2')->nullable();
            //TO DO: VER SI VALE LA PENA: creo que las siguientes fechas son para luego mostrar el periodo de la ausencia, ver si conviene hacerlo así, aunque seguramente será mejor de otra manera para no duplicar información en las tablas
            // $table->date('fecha_inicio')->nullable();
            // $table->date('fecha_fin')->nullable();
            $table->timestamps();

            $table->unique(['fecha', 'empleado_id','cuadrante_id']);

            $table->foreign('cuadrante_id')
                    ->references('id')->on('cuadrantes')
                    ->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lineas');
    }
}
