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

}
