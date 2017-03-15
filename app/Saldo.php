<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
  protected $guarded = [];

  public function empleado()
  {
    return $this->belongsTo('App\Empleado','empleado_id');
  }

}
