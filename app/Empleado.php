<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datetime;

class Empleado extends Model
{
    protected $table = 'empleados';
    
    public function scopeActivo($query)
    {
        return $query->where('activo', 1);
    }
    public function lineas()
    {
    	return 	$this->hasMany('App\Linea','empleado_id');
    }
    public function ausencias()
    {
        return  $this->hasMany('App\Ausencia','empleado_id');
    }
    public function saldos()
    {
        return  $this->hasMany('App\Saldo','empleado_id');
    }
    public function compensables()
    {
    	return 	$this->hasManyThrough('App\Compensable','App\Linea');
    }
    public function centro()
    {
    	return 	$this->belongsTo('App\Centro','centro_id');
    }

    public function getFechaAltaAttribute($value){
        if(!empty($value)){
        $value = Datetime::createFromFormat('Y-m-d',$value);
        return $value->format('d-m-Y');}
        return null;
    }
    public function getFechaBajaAttribute($value){
        if(!empty($value)){
        $value = Datetime::createFromFormat('Y-m-d',$value);
        return $value->format('d-m-Y');}
        return null;
    }






}
