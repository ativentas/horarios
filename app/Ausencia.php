<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ausencia extends Model
{
    protected $table = 'ausencias';
    
    public function empleado()
    {
    	return 	$this->belongsTo('App\Empleado','empleado_id');
    }
    public function lineas()
    {
    	return 	$this->hasMany('App\Linea','ausencia_id');
    }


}
