<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuadrante extends Model
{
    protected $table = 'cuadrantes';
    
    public function lineas()
    {
    	return 	$this->hasMany('App\Linea','cuadrante_id');
    }

    public function centro()
    {
    	return $this->belongsTo('App\Centro','centro_id');
    }
}
