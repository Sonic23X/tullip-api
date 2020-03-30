<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class TicketPostVenta extends Model
{
   protected $table = 'tickets_postventa';
   protected $casts=['detalles'=>'array'];

   const VALORACION = [
   				'PROCEDENTE' => 'Procedente',
   				'NO_PROCEDENTE' => 'No Procedente'
   			];

   const ESTATUS = [
   				'ABIERTO' => 'abierto',
   				'CERRADO' => 'cerrado'
   			];


   public function inmueble(){
   		return $this->belongsTo('App\Entities\Inmueble','id_inmueble');
   }
}
