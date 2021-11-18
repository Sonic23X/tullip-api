<?php

namespace App\Models;

use Facade\FlareClient\Http\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    use HasFactory, SoftDeletes;

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

    public function cliente()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller()
    {
        return $this->hasOne(User::class);
    }

    public function getTipoStringAttribute()
    {
        switch($this->attributes['tipo']) {
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
