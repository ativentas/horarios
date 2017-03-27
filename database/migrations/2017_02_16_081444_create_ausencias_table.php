<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAusenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ausencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empleado_id')->unsigned();
            $table->integer('centro_id')->unsigned();
            $table->integer('owner')->unsigned();
            $table->string('alias');
            $table->enum('tipo',['V','B','AJ','AN','BP'])->nullable();      
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->date('finalDay');
            $table->integer('dias')->nullable();
            $table->text('nota')->nullable();
            $table->enum('estado',['Pendiente','Confirmado'])->default('Pendiente');
            $table->boolean('allDay')->default(1);
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
        Schema::dropIfExists('ausencias');
    }
}
