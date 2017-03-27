<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id')->unsigned();
            //TO DO: BORRARLA DE LA BASE DE DATOS Y DE AQUÍ CUANDO TODO FUNCIONE
            // $table->integer('centro_id')->unsigned();
            $table->string('alias');
            $table->string('nombre_completo')->nullable();
            $table->string('apellidos')->nullable();
            $table->boolean('activo')->default(1);
            //TO DO: BORRARLA DE LA BASE DE DATOS, CDO FUNCIONE BIEN TODO
            // $table->date('fecha_alta')->nullable();
            // $table->date('fecha_baja')->nullable();
            $table->nullableTimestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
