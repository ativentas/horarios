<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $guarded = [];
  // user who has commented
  public function author()
  {
    return $this->belongsTo('App\User','from_user');
  }
  // returns post of any comment
  public function cuadrante()
  {
    return $this->belongsTo('App\Cuadrante','on_cuadrante');
  }

}
