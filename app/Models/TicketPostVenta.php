<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TicketPostVenta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tickets_postventa';
    protected $casts = [
        'detalles' => 'array'
    ];

    const VALORACION = [
        'PROCEDENTE' => 'Procedente',
        'NO_PROCEDENTE' => 'No Procedente'
    ];

    const ESTATUS = [
        'ABIERTO' => 'abierto',
        'CERRADO' => 'cerrado'
    ];

    public function inmueble()
    {
        return $this->belongsTo('App\Models\Inmueble', 'inmueble_id');
    }
}
