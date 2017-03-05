<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cuadrante extends Model
{
    protected $table = 'cuadrantes';

    protected $appends = ['semana','year','abarca'];

    
    public function lineas()
    {
    	return 	$this->hasMany('App\Linea','cuadrante_id');
    }
    public function lineacambios()
    {
    	return 	$this->hasMany('App\Lineacambio','cuadrante_id');
    }
    public function centro()
    {
    	return $this->belongsTo('App\Centro','centro_id');
    }
    
    public function getSemanaAttribute()
    {
        $semana = substr($this->yearsemana,-2,2);
        return $semana;
    }
    public function getYearAttribute()
    {
        $year = substr($this->yearsemana,0,4);
        return $year;
    }
    
    public function getAbarcaAttribute()
    {
        $year = substr($this->yearsemana,0,4);
        $semana = substr($this->yearsemana,-2,2);
        $date = new Carbon();
        $date = new Carbon($date->setISODate($year,$semana));
        $inicio_semana = new Carbon($date->startOfWeek());
        $inicio_semana = $inicio_semana->format('d M');
        $final_semana = new Carbon($date->endOfWeek());
        $final_semana = $final_semana->format('d M');
        $abarca = 'del '.$inicio_semana.' al '.$final_semana;
        return $abarca;
    }
}
