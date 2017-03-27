<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datetime;

class Contrato extends Model
{
  protected $guarded = [];

  public function empleado()
  {
		return $this->belongsTo('App\Empleado','empleado_id');
  }
  public function centro()
  {
  		return $this->belongsTo('App\Centro','centro_id');
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
