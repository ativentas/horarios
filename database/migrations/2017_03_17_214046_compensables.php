<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Compensables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compensables', function(Blueprint $table)
        {
            //TO DO: VER BIEN LOS CAMPOS QUE HAY QUE PONER
            $table->increments('id');
            $table->integer('linea_id')->unsigned()->unique();
            $table->text('nota')->nullable();
            $table->date('diacompensado')->nullable()->unique();
            $table->integer('cuadrante_id')->unsigned()->nullable();
            $table->boolean('pagar') -> default(0);
            $table->integer('resuelto_por') -> unsigned() -> nullable();
            $table->text('nota_respuesta')-> nullable();
            $table->boolean('visible') -> default(0);
            $table->timestamps();
            
            $table->foreign('linea_id')
                    ->references('id')->on('lineas')
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
        Schema::drop('compensables');
    }
}
