<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
        'direccion', 
        'user_id', 
        'days', 
        'email'
    ];

    /**
     * Get the admin
     */
    function admin()
    {
        return $this->hasOne('App\Models\User');
    }

    /**
     * Get all the users
     */
    function users()
    {
        return $this->hasMany('App\Models\User', 'empresa_id');     
    }
}
