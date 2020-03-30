<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Desarrollo extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'mapa_file'];

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
                // TODO: is this safe?
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
        return $this->hasMany('App\Entities\Prototipo', 'id_desarrollo');
    }

    /**
     * Inmuebles del desarrollo
     */
     public function inmuebles()
     {
         return $this->hasManyThrough('App\Entities\Inmueble', 'App\Entities\Prototipo', 'id_desarrollo', 'id_prototipo');
     }

    /**
     * Inmuebles del desarrollo (incluidos los de prototipos borrados)
     */
     public function inmueblesAll()
     {

        return Inmueble::join('prototipos', 'inmuebles.id_prototipo', '=', 'prototipos.id')
            ->where('prototipos.id_desarrollo','=',$this->id)
            ->whereNull('inmuebles.deleted_at')
            ->select('inmuebles.*')
            ->get();

     }
       public function inmueblesTitulados()
     {

        return Inmueble::join('prototipos', 'inmuebles.id_prototipo', '=', 'prototipos.id')
            ->where([
                        ['prototipos.id_desarrollo','=',$this->id],
                        ['inmuebles.status','titulado']
                    ])
            ->whereNull('inmuebles.deleted_at')
            ->select('inmuebles.*')
            ->get();

     }

    /**
     * The users that belong to the desarrollo.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
