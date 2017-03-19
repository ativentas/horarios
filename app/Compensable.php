<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compensable extends Model
{
	protected $guarded = [];
	protected $appends = ['empleado_id','dia'];


	public function linea()
	{
		return $this->belongsTo('App\Linea');
	}

	public function getEmpleadoIdAttribute(){

		$empleado_id =$this->linea->empleado_id;
		return $empleado_id;
	}
	public function getDiaAttribute(){
		$dia = $this->linea->fecha;
		return $dia;
	}


}
