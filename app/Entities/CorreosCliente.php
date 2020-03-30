<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class CorreosCliente extends Model
{
     /**
     * Name of the table
     *
     * @var string
     */
    protected $table = "correos_cliente";

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function Cliente(){
 		return $this->belongsTo('App\Entities\Cliente', 'id_cliente');
 	}

 	public function Seller(){
 		return $this->belongsTo('App\User', 'id_user');
 	}

}
