<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compensable extends Model
{
  protected $guarded = [];

  public function linea()
  {
    return $this->belongsTo('App\Linea','linea_id');
  }

}
