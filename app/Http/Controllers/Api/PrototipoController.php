<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prototipo;

class PrototipoController extends Controller
{
    public function getAll($desarrollo, $pageSize, $currentPage)
    {
        //tomar los prototipos del desarrollo
        $prototipos = null;

        if ($currentPage == '1') {
            $prototipos = Prototipo::where('desarrollo_id', $desarrollo)
                                ->select('id', 'nombre')
                                ->limit($pageSize)
                                ->get();

            $totalItem = Prototipo::where('desarrollo_id', $desarrollo)
                                ->limit($pageSize)
                                ->count();
        }
        else {
            $prototipos = Prototipo::where('desarrollo_id', $desarrollo)
                                ->select('id', 'nombre')
                                ->offset($pageSize * ($currentPage - 1))
                                ->limit($pageSize)
                                ->get();

            $totalItem = Prototipo::where('desarrollo_id', $desarrollo)
                                ->offset($pageSize * ($currentPage - 1))
                                ->limit($pageSize)
                                ->count();
        }

        $prototipoArray = [];

        //recorremos los prototipos y construimos el json de respuesta
        foreach ($prototipos as $prototipo) {
            $prototype =
            [
                'id' => $prototipo->id,
                'nombre' => $prototipo->nombre,
                'numInmuebles' => $prototipo->inmuebles()->count(),
            ];

            //introducimos a la respuesta
            array_push($prototipoArray, $prototype);
        }

        //responder a la peticion
        $response = [
            'status' => true,
            'totalItem' => $totalItem,
            'totalPage' => ceil(Prototipo::where('desarrollo_id', $desarrollo)->count() / $pageSize),
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'data' => $prototipoArray,
        ];

        return response()->json($response, 200);
    }

    public function get($id)
    {
        return response()->json(Prototipo::findOrFail($id), 200);
    }

    public function store(Request $request, $sembrado)
    {
        $prototipo = Prototipo::create(array_merge($request->validate([
            "nombre" => "required|max:255",
            "m2_construccion" => "numeric",
            "niveles" => "numeric",
            "recamaras" => "numeric",
            "baños" => "numeric",
            "observaciones" => '',
            "precio" => "numeric",
            "empresa_id" => "required"
        ]), ['desarrollo_id' => $sembrado]));

        $i = 0;
        $image = "file_";
        $fotos = [];

        while ($request->has($image . $i))
        {
            $file = $request->file($image . $i);
            $fotos[] = $file->store('prototipos/'.$prototipo->id.'/fotos', 'public');
            $i++;
        }

        //save the photos
        $prototipo->fotos = $fotos;
        $prototipo->save();

        return response()->json(['message' => '¡Prototipo creado!'], 201);
    }

    public function update(Request $request, $id)
    {
        $dataPrototype = $request->validate([
            "nombre" => "required|max:255",
            "m2_construccion" => "numeric",
            "niveles" => "numeric",
            "recamaras" => "numeric",
            "baños" => "numeric",
            "observaciones" => '',
            "precio" => "numeric"
        ]);

        //actualizar prototipo
        $prototipo = Prototipo::where('id', $id);
        $prototipo->update($dataPrototype);

        //si cargó nuevas fotos
        if ($request->has('file_0')) {
            $prototipo = Prototipo::findOrFail($id);

            //actualizamos las fotos
            $i = 0;
            $image = "file_";
            $fotos = [];

            while ($request->has($image . $i)) {
                $file = $request->file($image . $i);
                $fotos[] = $file->store('prototipos/'.$prototipo->id.'/fotos', 'public');
                $i++;
            }

            //save the photos
            $prototipo->fotos = $fotos;
            $prototipo->save();
        }

        return response()->json(['message' => '¡Prototipo actualizado!'], 200);
    }

    public function destroy($id)
    {
        \Storage::deleteDirectory('public/prototipos/' . $id);
        $prototipo = Prototipo::findOrFail($id)->delete();

        return response()->json(['message' => 'Prototype deleted!'], 200);
    }

    public function getDesarrolloPrototipos($desarrollo)
    {
        return response()->json(
            Prototipo::where('id_desarrollo', $desarrollo)->select('id', 'nombre', 'precio')->get()
        , 200);
    }

    public function getPrototiposToClone($desarrollo, $empresa)
    {
        $prototipos = Prototipo::where('empresa_id', $empresa)
                            ->where('desarrollo_id', '<>', $desarrollo)
                            ->select('id', 'nombre')
                            ->get();

        return response()->json($prototipos, 200);
    }

    public function clonarPrototipo($id, $desarrollo)
    {
        $prototipoClone = Prototipo::findOrFail($id);

        $prototipo = Prototipo::create([
            'nombre' => $prototipoClone->nombre,
            'm2_construccion' => $prototipoClone->m2_construccion,
            'niveles' => $prototipoClone->niveles,
            'recamaras' => $prototipoClone->recamaras,
            'baños' => $prototipoClone->baños,
            'observaciones' => $prototipoClone->observaciones,
            'precio' => $prototipoClone->precio,
            'fotos' =>$prototipoClone->fotos,
            'empresa_id' => $prototipoClone->id_empresa,
            'desarrollo_id' => $desarrollo,
        ]);

        return response()->json(['message' => '¡Prototipo clonado!'], 200);
    }
}
