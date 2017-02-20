<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Festivo extends Model
{
    protected $table = 'festivos';
    public $timestamps = false;
    
    public function lineas()
    {
    	return 	$this->hasMany('App\Linea','fecha','fecha');
    }

}
