<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ClienteInmueble extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Name of the table
     *
     * @var string
     */
    protected $table = "cliente_inmueble";

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function prospect()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function lote(){
        return $this->belongsTo('App\Models\Inmueble');
    }
}
