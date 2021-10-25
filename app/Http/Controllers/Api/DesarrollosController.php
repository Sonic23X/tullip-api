<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    Desarrollo,
    Prototipo,
    Inmueble,
    User
};

class DesarrollosController extends Controller
{
    public function getDesarrollos($pageSize, $currentPage, $empresa)
    {
        $desarollo = null;
        $totalItem = null;

        if ($currentPage == "1") {
            $desarollo = Desarrollo::limit($pageSize)
                                ->where('empresa_id', $empresa)
                                ->get();

            $totalItem = Desarrollo::limit($pageSize)
                                ->where('empresa_id', $empresa)
                                ->count();
        }
        else {
            $desarollo = Desarrollo::offset($pageSize * ($currentPage - 1))
                                ->where('empresa_id', $empresa)
                                ->limit($pageSize)
                                ->get();

            $totalItem = Desarrollo::offset($pageSize * ($currentPage - 1))
                                ->where('empresa_id', $empresa)
                                ->limit($pageSize)
                                ->count();
        }

        $response = [
            'status' => true,
            'totalItem' => $totalItem,
            'totalPage' => ceil(Desarrollo::where('empresa_id', $empresa)->count() / $pageSize),
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'data' => $desarollo
        ];

        return $response;
    }

    public function getOnlyDesarrollo($empresa)
    {
        return response()->json(Desarrollo::where('empresa_id', $empresa)->select('id', 'nombre')->get(), 200);
    }

    public function getDesarrollo($id)
    {
        $desarrollo = Desarrollo::select('id', 'nombre')->findOrFail($id);
        $NumPrototipos = $desarrollo->prototipos()->count();
        $NumInmuebles = $desarrollo->inmueblesAll()->count();
        return response()->json(array('desarrollo' => $desarrollo, 'prototipos' => $NumPrototipos, 'inmuebles' => $NumInmuebles), 200);
    }

    public function getSembrado($id)
    {
      return response()->json(Desarrollo::find($id), 200);
    }

    public function storeDesarrollo(Request $request)
    {
        $input = $request->validate(
        [
            'nombre'    => 'required|min:3|max:255',
            'empresa_id' => 'required',
            'mapa_file'  => 'required|file'
        ]);

        Desarrollo::create($input);

        return response()->json(['message' => 'Desarrollo creado'], 201);
    }

    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'nombre' => "required|unique:desarrollos,nombre,$id|min:3|max:255",
            'mapa_file' => 'file'
        ]);

        $desarrollo = Desarrollo::findOrFail($id);
        $desarrollo->fill($input);
        $desarrollo->save();

        return response()->json($desarrollo, 200);
    }

    public function delete($id)
    {
        $desarrollo = Desarrollo::findOrFail($id);
        $desarrollo->delete();

        return response()->json(['message' => 'desarrollo deleted'], 200);
    }

    public function updateMap( Request $request, $desarrollo )
    {
        $data = $request->validate([
            'width' => 'required'
        ]);

        $des = Desarrollo::find($desarrollo);
        $des->width = $data['width'];
        $des->save();

        //actualizamos los puntos en el mapa
        $i = 0;
        $name = 'point_';

        while ($request->has($name . $i)) {
            //obtenemos el id, el punto x y punto y
            $pointData = explode('_', $request[$name . $i]);
            $x = str_replace('px', '', $pointData[1]);
            $y = str_replace('px', '', $pointData[2]);

            $inmueble = Inmueble::find($pointData[0]);
            $inmueble->_map_x = $x != '' ? $x : NULL;
            $inmueble->_map_y = $y != '' ? $y : NULL;

            $inmueble->save();
            $i++;
        }

        return response()->json(['message' => '¡Mapa actualizado!'], 200);
    }
}
