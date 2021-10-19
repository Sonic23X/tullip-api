<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Contabilidad extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Name of the table
     *
     * @var string
     */
    protected $table = 'contabilidad';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'cliente_id', 
        'desarrollo_id', 
        'inmueble_id', 
        'fecha_pago', 
        'monto',
        'tipo', 
        'empresa_id',
    ];
}
