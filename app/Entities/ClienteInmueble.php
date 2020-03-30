<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ClienteInmueble extends Model
{
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

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function prospect(){
 		return $this->belongsTo('App\Entities\Cliente', 'id_cliente');
 	}

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function lote(){
 		return $this->belongsTo('App\Entities\Inmueble', 'id_inmueble');
 	}
}
