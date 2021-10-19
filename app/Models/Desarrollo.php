<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Desarrollo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
        'mapa_file', 
        'width', 
        'empresa_id'
    ];

    /**
     * Set the mapa file.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setMapaFileAttribute($value)
    {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            if (!empty($this->mapa_file)) {
                \Storage::delete($this->attributes['mapa_file']);
            }
            $value = $value->storeAs('mapas', $this->nombre);
        }

        $this->attributes['mapa_file'] = $value;
    }

    /**
     * Prototipos del desarrollo.
     */
    public function prototipos()
    {
        return $this->hasMany('App\Models\Prototipo');
    }

    /**
     * Inmuebles del desarrollo
     */
    public function inmuebles()
    {
        return $this->hasManyThrough('App\Models\Inmueble', 'App\Models\Prototipo');
    }

    /**
     * Inmuebles del desarrollo (incluidos los de prototipos borrados)
     */
    public function inmueblesAll()
    {
        return Inmueble::join('prototipos', 'inmuebles.id_prototipo', '=', 'prototipos.id')
                    ->where('prototipos.id_desarrollo', '=', $this->id)
                    ->whereNull('inmuebles.deleted_at')
                    ->select('inmuebles.*')
                    ->get();
    }

    /**
     * Inmuebles unicamente titulados
     */
    public function inmueblesTitulados()
    {
        return Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                    ->where('prototipos.desarrollo_id', '=', $this->id)
                    ->where('inmuebles.status', 'titulado')
                    ->whereNull('inmuebles.deleted_at')
                    ->select('inmuebles.*')
                    ->get();
    }

    /**
     * The users that belong to the desarrollo.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
