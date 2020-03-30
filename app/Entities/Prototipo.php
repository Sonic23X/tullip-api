<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prototipo extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fotos' => 'array',
    ];

    /**
     * Inmuebles del prototipo.
     */
    public function inmuebles()
    {
        return $this->hasMany('App\Entities\Inmueble', 'id_prototipo');
    }

    /**
     * Desarrollo del prototipo.
     */
    public function desarrollo()
    {
        return $this->belongsTo('App\Entities\Desarrollo', 'id_desarrollo');
    }
}
