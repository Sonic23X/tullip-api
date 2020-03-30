<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use stdClass;

class Cliente extends Model
{
    use SoftDeletes;
    protected $guarded = array('id');

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function trackings(){
        return $this->hasMany('App\Entities\Seguimiento', 'id_cliente');
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

    public function correos(){
        return $this->hasMany('App\Entities\CorreosCliente', 'id_cliente');
    }


    public function desarrollo(){
        return $this->belongsTo('App\Entities\Desarrollo', 'id_desarrollo');
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function documentos(){
        return $this->hasMany('App\Entities\Documento', 'id_cliente');
    }

    public function payments(){
		return $this->hasMany('App\Entities\ClienteInmueble', 'id_cliente');
	}

	public function lotes(){
		return $this->hasMany('App\Entities\Inmueble', 'id_prospecto');
	}
    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function getDocumentsAttribute(){
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

   

    /**
     * Get the anexo_detalles.
     *
     * @param  string  $value
     * @return stdClass
     */
    public function getAnexoDetallesAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_detalles.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAnexoDetallesAttribute($value)
    {
        $this->attributes['anexo_detalles'] = json_encode($value);
    }

    /**
     * Get the anexo_trabajo.
     *
     * @param  string  $value
     * @return stdClass
     */
    public function getAnexoTrabajoAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_trabajo.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAnexoTrabajoAttribute($value)
    {
        $this->attributes['anexo_trabajo'] = json_encode($value);
    }

    /**
     * Get the anexo_trabajo.
     *
     * @param  string  $value
     * @return stdClass
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
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAnexoCreditoAttribute($value)
    {
        $this->attributes['anexo_credito'] = json_encode($value);
    }

    /**
     * Get the anexo_referencias.
     *
     * @param  string  $value
     * @return stdClass
     */
    public function getAnexoReferenciasAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_referencias.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAnexoReferenciasAttribute($value)
    {
        $this->attributes['anexo_referencias'] = json_encode($value);
    }

    /**
     * Get the anexo_conyuge.
     *
     * @param  string  $value
     * @return stdClass
     */
    public function getAnexoConyugeAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set the anexo_conyuge.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAnexoConyugeAttribute($value)
    {
        $this->attributes['anexo_conyuge'] = json_encode($value);
    }

    /**
     * Get the fecha nacimiento.
     *
     * @param  string  $value
     * @return STDObject
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

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function setCompletedPercent(){
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
