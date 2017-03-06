<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    
    public function getEntrada1Attribute($value){
        return substr($value,0,5);

    }
    public function getSalida1Attribute($value){
        return substr($value,0,5);

    }
    public function getEntrada2Attribute($value){
        return substr($value,0,5);

    }
    public function getSalida2Attribute($value){
        return substr($value,0,5);

    }
    public function getFechaAttribute($value){
        $value = Carbon::createFromFormat('Y-m-d',$value);
        return $value->format('d-m-Y');
    }
    public function setFechaAttribute ($value){
        $this->attributes['fecha'] = Carbon::createFromFormat('d-m-Y',$value);
    }
}
