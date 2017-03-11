<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Ausencia extends Model
{
   protected $table = 'ausencias';
   protected $appends = ['abarca'];

    
    public function empleado()
    {
    	return 	$this->belongsTo('App\Empleado','empleado_id');
    }
    public function lineas()
    {
    	return 	$this->hasMany('App\Linea','ausencia_id');
    }
    public function getAbarcaAttribute()
    {

        $inicio_semana = new Carbon($this->fecha_inicio);
        $inicio_semana = $inicio_semana->format('d M');
        $final_semana = new Carbon($this->finalDay);
        $final_semana = $final_semana->format('d M');
        $abarca = $inicio_semana.' al '.$final_semana;
        return $abarca;
    }

    // returns all comments on that post
    public function comments()
    {
    return $this->  hasMany('App\Comment','on_ausencia');
    }
    // returns the instance of the user who is author of that post
    public function author()
    {
    return $this->belongsTo('App\User','author_id');
    }


}
