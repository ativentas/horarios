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
            $table->enum('situacion',['V','FT','B','AJ','AN','L','VT'])->nullable();
            $table->time('entrada1')->nullable();
            $table->time('salida1')->nullable();
            $table->time('entrada2')->nullable();
            $table->time('salida2')->nullable();
            //ahora no se para que sirven las siguientes fechas, de momento las borro de la tabla
            // $table->date('fecha_inicio')->nullable();
            // $table->date('fecha_fin')->nullable();
            $table->timestamps();

            $table->unique(['fecha', 'empleado_id']);

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
