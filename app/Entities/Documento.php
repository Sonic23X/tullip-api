<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    /**
     * TODO: Used somewhere?
     *
     * @var string
     */
    const DOCUMENT_LIST = [
        'identificacion',
        'comprobante_domicilio',
        'acta_nacimiento',
        'curp',
        'talones_pago',
        'estado_cuenta_sar'
    ];

    /**
     * undocumented class variable
     *
     * @var string
     */
    public $fillable = ['id_cliente', 'nombre', 'id_usuario'];

    /**
     * Name of the table
     *
     * @var string
     */
    protected $table = "documentos";

    /**
     * Get the url to the resource.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('documentos.show', ['id' => $this->id]);
    }
}
