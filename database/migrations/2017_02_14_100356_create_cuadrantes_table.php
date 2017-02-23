<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuadrantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuadrantes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('yearsemana');
            $table->integer('centro_id')->unsigned();
            $table->boolean('archivado')->default(0);
            $table->enum('dia_1',['A','C','FA','FC'])->defautl('A');
            $table->enum('dia_2',['A','C','FA','FC'])->defautl('A');
            $table->enum('dia_3',['A','C','FA','FC'])->defautl('A');
            $table->enum('dia_4',['A','C','FA','FC'])->defautl('A');
            $table->enum('dia_5',['A','C','FA','FC'])->defautl('A');
            $table->enum('dia_6',['A','C','FA','FC'])->defautl('A');
            $table->enum('dia_0',['A','C','FA','FC'])->defautl('A');
            $table->nullableTimestamps();

            $table->unique(['yearsemana', 'centro_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuadrantes');
    }
}
