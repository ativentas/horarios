<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lineacambio extends Model
{
    protected $table = 'lineacambios';

    protected $guarded = ['id','updated_at','created_at'];
    
    protected $appends = ['dia_texto'];

    public function cuadrante()
    {
    	return 	$this->belongsTo('App\Cuadrante','cuadrante_id');
    }
    public function ausencia()
    {
    	return 	$this->belongsTo('App\Ausencia','ausencia_id');
    }
    public function festivo()
    {
    	return $this->belongsTo('App\Festivo','fecha','fecha');
    }
    function getDiaTextoAttribute() {
        $array=['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
        return $array[$this->dia];
    }

    public function linea()
    {
        return $this->belongsTo('App\Linea');
    }
}