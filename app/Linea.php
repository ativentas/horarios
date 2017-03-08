<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Linea extends Model
{
    protected $table = 'lineas';

    protected $guarded = ['id','updated_at','created_at'];
    
    protected $appends = ['dia_texto','horasdiarias'];

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
    function getHorasdiariasAttribute() {
 
        // if($this->entrada2){
        $nextDay1=$this->entrada1>$this->salida1?1:0;
        $nextDay2=$this->entrada2>$this->salida2?1:0;
        $dep1=explode(':',$this->entrada1?:'00:00');
        $arr1=explode(':',$this->salida1?:'00:00');        
        $dep2=explode(':',$this->entrada2?:'00:00');
        $arr2=explode(':',$this->salida2?:'00:00');
        $diff=ABS(MKTIME($dep1[0],$dep1[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr1[0],$arr1[1],0,DATE('n'),DATE('j')+$nextDay1,DATE('y'))+MKTIME($dep2[0],$dep2[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr2[0],$arr2[1],0,DATE('n'),DATE('j')+$nextDay2,DATE('y')));
        $hours=FLOOR($diff/(60*60));
        $mins=FLOOR(($diff-($hours*60*60))/(60));
        IF(STRLEN($hours)<2){$hours="0".$hours;}
        IF(STRLEN($mins)<2){$mins="0".$mins;}
        return $hours.':'.$mins;
        // }
        
    }

    public function lineacambio()
    {
        return $this->hasOne('App\Lineacambio');
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
}
