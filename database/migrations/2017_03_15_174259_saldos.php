<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Saldos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldos', function(Blueprint $table)
        {
          $table->increments('id');
          $table->integer('year');
          $table->integer('empleado_id')->unsigned();
          $table->integer('dias');
          $table->text('nota')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('saldos');
    }
}
