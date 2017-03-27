<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = 'centros';

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

    public function empleados()
    {
        return $this->belongsToMany('App\Empleado','contratos');
    }

    public function ausencias()
    {
        return $this->hasMany('App\Ausencia','centro_id');
    }

}
