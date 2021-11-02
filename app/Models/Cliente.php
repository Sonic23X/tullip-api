<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Buscar los seguimientos
     */
    public function trackings()
    {
        return $this->hasMany('App\Models\Seguimiento', 'cliente_id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The documents definitions.
     *
     * @var array
     */
    public $docsdef = [
        'identificacion',
        'validacion_ine',
        'comprobante_domicilio',
        'acta_nacimiento',
        'curp',
        'rfc',
        'acta_matrimonio',
        'identificacion_conyuge',
        'validacion_ine_conyuge',
        'curp_conyuge',
        'acta_nacimiento_conyuge',
        'rfc_conyuge',
        'taller_saber_para_decidir',
        'precalificacion',
        'generales_del_cliente'
    ];

    /**
     * The documents definitions.
     *
     * @var array
     */
    public $docsdeffovisste = [
        'talones_pago',
        'archivo_trabajo_electronico',
        'estado_cuenta_sar'
    ];

    public function seller()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function desarrollo()
    {
        return $this->belongsTo('App\Models\Desarrollo',);
    }

    public function documentos()
    {
        return $this->hasMany('App\Models\Documento');
    }

    public function payments(){
		return $this->hasMany('App\Models\ClienteInmueble');
	}

	public function lotes()
    {
		return $this->hasMany('App\Models\Inmueble', 'prospecto_id');
	}

    public function getDocumentsAttribute()
    {
        $documents = new stdClass;

        $defs = array_merge($this->docsdef, $this->docsdeffovisste);
        foreach ($defs as $def) {
            $documents->$def = null;
        }

        if ($this->documentos()->count() > 0) {
            foreach ($this->documentos as $documento) {
                $documents->{$documento->nombre} = $documento;
            }
        }

        return $documents;
    }

    public function getAnexoDetallesAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_detalles.
     */
    public function setAnexoDetallesAttribute($value)
    {
        $this->attributes['anexo_detalles'] = json_encode($value);
    }

    /**
     * Get the anexo_trabajo.
     */
    public function getAnexoTrabajoAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_trabajo.
     */
    public function setAnexoTrabajoAttribute($value)
    {
        $this->attributes['anexo_trabajo'] = json_encode($value);
    }

    /**
     * Get the anexo_trabajo.
     */
    public function getAnexoCreditoAttribute($value)
    {
        $value = json_decode($value);

        if(isset($value->tipo_credito)){

            switch($value->tipo_credito){
                case 'Infonavit Tradicional':
                case 'Infonavit Total':
                case 'Infonavit 2do Crédito':
                    $credito = 'infonavit';
                    break;

                case 'Cofinavit':
                case 'Cofinavit IA':
                    $credito = 'cofinavit';
                    break;

                case 'Apoyo Infonavit':
                    $credito = 'apoyo_infonavit';
                    break;

                case 'Fovissste Tradicional':
                case '2do Crédito Fovissste':
                    $credito = 'fovissste';
                    break;

                case 'Alia2 Plus':
                    $credito = 'alia2';
                    break;

                case 'Bancarios':
                    $credito = 'bancario';
                    break;

                case 'Isssfam':
                case 'Caprepol':
                default:
                    $credito = 'otro';
                    break;
            }
            $array_credito['ingresos'] = $value->ingresos ?? '';
            $array_credito['pension_alimenticia'] = $value->pension_alimenticia ?? '';
            unset($value->ingresos);
            unset($value->pension_alimenticia);
            $array_credito["{$credito}"] = [$value];
            $value = json_decode(json_encode($array_credito));
        }
        return $value;

    }

    /**
     * Set the anexo_trabajo.
     */
    public function setAnexoCreditoAttribute($value)
    {
        $this->attributes['anexo_credito'] = json_encode($value);
    }

    /**
     * Get the anexo_referencias.
     */
    public function getAnexoReferenciasAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_referencias.
     */
    public function setAnexoReferenciasAttribute($value)
    {
        $this->attributes['anexo_referencias'] = json_encode($value);
    }

    /**
     * Get the anexo_conyuge.
     */
    public function getAnexoConyugeAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_conyuge.
     */
    public function setAnexoConyugeAttribute($value)
    {
        $this->attributes['anexo_conyuge'] = json_encode($value);
    }

    /**
     * Get the fecha nacimiento.
     */
    public function getFechaNacimientoAttribute()
    {
        $date = new \Carbon\Carbon('0001-01-01');

        if (isset($this->anexo_detalles->fecha_nacimiento)) {
            try {
                $date = new \Carbon\Carbon($this->anexo_detalles->fecha_nacimiento);
            } catch(\Exception $e) {
                $date = new \Carbon\Carbon('0001-01-01');
            }
        }

        return $date;
    }

    public function setCompletedPercent()
    {
		$completed = 20;
		$steps_completed = 0;

		$total_steps = 3;

		if( $this->credit ){
			$steps_completed++;
		}

		if( $this->work ){
			$steps_completed++;
		}

		if( $this->references ){
			$steps_completed++;
		}

		$remaining_percent = 100 - $completed;

		$completed += ($remaining_percent * ( $steps_completed / $total_steps ));

		$this->completado  = $completed;
		$this->save();
	}
}
