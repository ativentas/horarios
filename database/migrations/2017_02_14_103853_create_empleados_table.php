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
            $table->string('alias');
            $table->string('nombre_completo')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('telefono')->nullable();
            $table->boolean('activo')->default(1);

            $table->nullableTimestamps();

            $table->unique(['nombre_completo', 'apellidos']);

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
