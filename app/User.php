<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'activo' => 'boolean',
    ];


    public function isAdmin()
    {
        return $this->is_admin;
    }
    public function isActivo()
    {
        return $this->activo;
    }
    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }    
    public function cuadrantes()
    {
        return  $this->hasMany('App\Cuadrante','user_id');
    }

    public function cuadrantesP()
    {
        return $this->hasMany('App\Cuadrante','author_id');
    }
    
    public function centro()
    {
        return $this->belongsTo('App\Centro','centro_id');
    }

    // user has many comments
    public function comments()
    {
        return $this->hasMany('App\Comment','from_user');
    }
    public function respuestas()
    {
        return $this->hasMany('App\Comment','resuelto_por');
    }
    public function scopeNormal($query)
    {
        return $query->where('is_admin',0);
    }
    // public function can_post()
    // {
    // $role = $this->role;
    // if($role == 'author' || $role == 'admin')
    // {
    // return true;
    // }
    // return false;
    // }




}
