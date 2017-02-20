<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePredefinidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predefinidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('centro_id')->unsigned();
            $table->string('nombre');
            $table->time('entrada1')->nullable();
            $table->time('salida1')->nullable();
            $table->time('entrada2')->nullable();
            $table->time('salida2')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predefinidos');
    }
}
