<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    /**
     * Name of the table
     *
     * @var string
     */
    protected $table = "documentos";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cliente_id', 
        'nombre', 
        'user_id', 
        'empresa_id'
    ];

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
