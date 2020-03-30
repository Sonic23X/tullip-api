<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    const TIPO_NUEVO = "nuevo";
	const TIPO_CITA = "cita";
	const TIPO_LLAMADA = "llamada";
	const TIPO_VISITA_FRACCIONAMIENTO = "visita_fraccionamiento";
	const TIPO_COMENTARIO = "comentario";

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
    public function cliente(){
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
    public function seller(){
 		return $this->belongsTo('App\User', 'id_usuario');
 	}

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
     public function getTipoStringAttribute(){
 		switch($this->attributes['tipo']){
 			case self::TIPO_CITA:
 				return 'Cita';
 			case self::TIPO_LLAMADA:
 				return 'Llamada';
 			case self::TIPO_NUEVO:
 				return 'Prospecto Creado';
 			case self::TIPO_VISITA_FRACCIONAMIENTO:
 				return 'Visita al fraccionamiento';
 			case self::TIPO_COMENTARIO:
 				return '';
 			default:
 				return $this->attributes['tipo'];
 		}

 		return $this->attributes['tipo'];
 	}
}
