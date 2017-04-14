<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = 'centros';

    protected $appends = ['dia'];

    public $timestamps = false;
    
    public function cuadrantes()
    {
    	return 	$this->hasMany('App\Cuadrante','centro_id');
    }

    // public function ausencias()
    // {
    //     return $this->hasManyThrough('App\Ausencia', 'App\Empleado');
    // }


    public function contratos()
    {
        return $this->hasMany('App\Contrato','centro_id');
    }

    public function predefinidos()
    {
        return $this->hasMany('App\Predefinido','centro_id');
    }

    public function empleados()
    {
        return $this->belongsToMany('App\Empleado','contratos');
    }

    public function ausencias()
    {
        return $this->hasMany('App\Ausencia','centro_id');
    }
    public function getDiaAttribute()
    {
        $dowMap = array('Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab');
        if(!is_null($this->dia_cierre)){
            $dia = $dowMap[$this->dia_cierre];
            return $dia;
        }return false;
    }

}
