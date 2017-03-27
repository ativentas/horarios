<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function(Blueprint $table)
        {
            //TO DO: VER BIEN LOS CAMPOS QUE HAY QUE PONER
            $table->increments('id');
            $table->integer('centro_id')->unsigned();
            $table->integer('empleado_id')->unsigned();
            $table->date('fecha_alta');
            $table->date('fecha_baja')->nullable();
            $table->timestamps();

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
