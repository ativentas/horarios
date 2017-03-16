<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $guarded = [];

  protected $casts = [
    'is_solved' => 'boolean',

    ];

  // user who has commented
  public function author()
  {
    return $this->belongsTo('App\User','from_user');
  }
  public function resolvedor()
  {
    return $this->belongsTo('App\User','resuelto_por');
  }
  // returns post of any comment
  public function cuadrante()
  {
    return $this->belongsTo('App\Cuadrante','on_cuadrante');
  }
  public function ausencia()
  {
    return $this->belongsTo('App\Ausencia','on_ausencia');
  }



  public function isSolved()
  {
      return $this->resuelto;
  }





}
