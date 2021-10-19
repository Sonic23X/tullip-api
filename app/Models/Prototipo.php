<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Prototipo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'm2_construccion',
        'niveles',
        'recamaras', 
        'baños',
        'observaciones',
        'precio',
        'fotos',
        'empresa_id',
        'desarrollo_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

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
        return $this->hasMany('App\Models\Inmueble');
    }

    /**
     * Desarrollo del prototipo.
     */
    public function desarrollo()
    {
        return $this->belongsTo('App\Models\Desarrollo');
    }
}
