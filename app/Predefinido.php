<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Predefinido extends Model
{
    protected $table = 'predefinidos';

    public $timestamps = false;
    
    public function centro()
    {
    	return $this->belongsTo('App\Centro','centro_id');
    }
    
//solo funciona cuando se hace con Eloquent no con raw query
    public function getEntrada1Attribute($value)
    {
    	return substr($value,0,3);
    }
}
