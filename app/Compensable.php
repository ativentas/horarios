<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compensable extends Model
{
	protected $guarded = [];
	protected $appends = ['empleado_id','dia','disponible'];


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

	public function getDisponibleAttribute(){
		if($this->diacompensado==null&&$this->pagar==false){
			return true;
		}
		return false;
	}

	public function empleado(){
		return $this->belongsTo('App\Empleado','empleado_id');
	}


}
