<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inmueble extends Model
{
    use SoftDeletes;
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

    protected $guarded = [
        'id'
    ];
    protected $casts = [
        'citas_entrega'=>'array',
        'documentos'=>'array'
        ];
    protected $dates = ['deleted_at'];

    const STATUS_LIBRE = 'libre';
    const STATUS_APARTADO = 'apartado';
    const STATUS_VENCIDO = 'vencido';
    const STATUS_TITULADO = 'titulado';

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    static function getPrecio($month) {
        foreach(self::PRECIOS as $precio){
             if($month >= $precio['mensualidades_min']) {
                     if($month <= $precio['mensualidades_max']){
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
        return $this->belongsTo('App\Entities\Cliente', 'id_prospecto');
    }

    /**
     * Prototipo del Inmueble.
     */
    public function prototipo()
    {
        return $this->belongsTo('App\Entities\Prototipo', 'id_prototipo');
    }

     public function vendedor()
    {
        return $this->belongsTo('App\User', 'id_vendedor');
    }
    public function tickets()
    {
        return $this->hasMany('App\Entities\TicketPostVenta','id_inmueble');
    }


    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function getProspectoAttribute()
    {
        return $this->cliente();
    }
}
