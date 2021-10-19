<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Inmueble extends Model
{
    use HasFactory, SoftDeletes;

    const PRECIOS = [
        [
            'nombre' => 'Contado',
            'precio_m2' => 4000,
            'enganche' => 30,
            'mensualidades' => 0,
            'mensualidades_min' => 0,
            'mensualidades_max' => 0
        ],
        [
            'nombre' => 'Credito 12 meses',
            'precio_m2' => 4500,
            'enganche' => 30,
            'mensualidades' => 12,
            'mensualidades_min' => 1,
            'mensualidades_max' => 12
        ],
        [
            'nombre' => 'Credito 24 meses',
            'precio_m2' => 5000,
            'enganche' => 30,
            'mensualidades' => 24,
            'mensualidades_min' => 13,
            'mensualidades_max' => 24
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_prototipo',
        'manzana',
        'lote',
        'calle',
        'numero',
        'numero_interior', 
        'm2_terreno',
        'status',
        'medidas_1',
        'medidas_2',
        'medidas_3',
        'medidas_4',
        'colindancia_1',
        'colindancia_2',
        'colindancia_3',
        'colindancia_4',
        'orientacion_1',
        'orientacion_2',
        'orientacion_3',
        'orientacion_4',
        '_map_x',
        '_map_y',
        'id_vendedor',
        'id_prospecto',
        'fecha_apartado',
        'apartado_pagado',
        'precio',
        'apartado_enganche',
        'apartado_mensualidades',
        'modo_apartado',
        'precio_venta',
        'monto_mensualidad',
        'monto_pagado',
        'pagos_pagados',
        'ultimo_pago',
        'fecha_titulado',
        'citas_entrega',
        'entrega',
        'fecha_entrega',
        'documentos'
    ];

    protected $casts = [
        'citas_entrega' => 'array',
        'documentos'    => 'array'
    ];

    protected $dates = [
        'deleted_at'
    ];

    static function getPrecio($month) 
    {
        foreach (self::PRECIOS as $precio) {
            if ($month >= $precio['mensualidades_min']) {
                if ($month <= $precio['mensualidades_max']) {
                    return $precio;
                }
            }
        }
    }

    /**
     * Cliente del inmueble.
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    /**
     * Prototipo del Inmueble.
     */
    public function prototipo()
    {
        return $this->belongsTo('App\Models\Prototipo');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\TicketPostVenta');
    }

    public function getProspectoAttribute()
    {
        return $this->cliente();
    }

}
