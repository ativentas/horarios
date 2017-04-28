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
    public function compensables()
    {
        return $this->hasMany('App\Compensable','empleado_id');
    }
    public function saldos()
    {
        return  $this->hasMany('App\Saldo','empleado_id');
    }
    
    //Como ahora he añadido el campo empleado_id a la tabla compensables, entonces ahora la funcion compensables() es mas sencilla   
    // public function compensables()
    // {
    // 	return 	$this->hasManyThrough('App\Compensable','App\Linea');
    // }

    //TO DO: ESTA RELACION CREO QUE HABRÁ QUE ELIMINARLA, ESTOY CAMBIANDO PARA QUE LOS EMPLEADOS TENGAN UN CONTRATO VIGENTE
    // public function centro()
    // {
    // 	return 	$this->belongsTo('App\Centro','centro_id');
    // }

    //relacion Many to Many, contratos es la tabla pivot
    public function centros()
    {
        return $this->belongsToMany('App\Centro','contratos');
    }
    
    public function centro()
    {
        $hoy = new Datetime();
        $hoy = $hoy->format('Y-m-d');
        $centro = $this->belongsToMany('App\Centro','contratos')
            ->where([
            ['fecha_baja',NULL],
            ['fecha_alta','<=',$hoy],
            ])
            ->orWhere([
            ['fecha_baja','>=',$hoy],
            ['fecha_alta','<=',$hoy],
            ]);

        // ->orWherePivot('fecha_baja','>=',$hoy)
        if($centro){
        return $centro;}
        return false;
    }

    public function ultimo_centro()
    {
        $hoy = new Datetime();
        $hoy = $hoy->format('Y-m-d');
        $centros = $this->belongsToMany('App\Centro','contratos')
            ->where('fecha_alta','<=',$hoy)->orderBy('fecha_alta','desc');

        if($centros){
            return $centros;
        }
        return false;
    }

    // public function centro_fecha($fecha)
    // {
    //     $fecha = DateTime::createFromFormat('d/m/Y', $fecha);
    //     $fecha = $fecha->format('Y-m-d');
    //     $centro = $this->belongsToMany('App\Centro','contratos')->where([
    //         ['fecha_baja',NULL],
    //         ['fecha_alta','<=',$fecha],
    //         ])->orWhere('fecha_baja','>=',$fecha);

    //     // ->orWherePivot('fecha_baja','>=',$hoy)
    //     if($centro){
    //     return $centro;}
    //     return false;
    // }

    // public static function scopeCentro_fecha($centro, $fecha)
    // {
    //     $fecha = DateTime::createFromFormat('d/m/Y', $fecha);
    //     $fecha = $fecha->format('Y-m-d');

    //     return $centro->whereHas('contratos', function($q) use($fecha){
    //         $q->where([
    //             ['fecha_baja',NULL],
    //             ['fecha_alta','<=',$fecha],
    //             ])->orWhere('fecha_baja','>=',$fecha);
    //     });
        // $centro = $this->belongsToMany('App\Centro','contratos')->where([
        //     ['fecha_baja',NULL],
        //     ['fecha_alta','<=',$fecha],
        //     ])->orWhere('fecha_baja','>=',$fecha);

        // // ->orWherePivot('fecha_baja','>=',$hoy)
        // if($centro){
        // return $centro;}
        // return false;
    // }

    public function contratos()
    {
        return $this->hasMany('App\Contrato','empleado_id');
    }
    public function contrato_actual()
    {
        $hoy = new Datetime();
        $hoy = $hoy->format('Y-m-d');
        return $this->hasMany('App\Contrato','empleado_id')->where([
            ['fecha_baja',NULL],
            ['fecha_alta','<=',$hoy],
            ])->orWhere('fecha_baja','>=',$hoy);
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
