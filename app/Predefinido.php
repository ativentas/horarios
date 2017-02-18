<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Predefinido extends Model
{
    protected $table = 'predefinidos';
    
    public function centro()
    {
    	return 	$this->belongsTo('App\Centro','centro_id');
    }

}
