<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    Inmueble,
    Prototipo,
    Cliente,
    Seguimiento
};

class InmuebleController extends Controller
{
    public function getAll($desarrollo, $pageSize, $currentPage)
    {
        if ($currentPage == '1') {
            $inmuebles = Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                             ->where('prototipos.id_desarrollo', '=', $desarrollo)
                             ->whereNull('inmuebles.deleted_at')
                             ->select('inmuebles.*', 'prototipos.nombre')
                             ->limit($pageSize)
                             ->get();

            $totalItem = Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                             ->where('prototipos.id_desarrollo', '=', $desarrollo)
                             ->whereNull('inmuebles.deleted_at')
                             ->select('inmuebles.*', 'prototipos.nombre')
                             ->limit($pageSize)
                             ->count();
        }
        else
        {
            $inmuebles = Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                             ->where('prototipos.id_desarrollo', '=', $desarrollo)
                             ->whereNull('inmuebles.deleted_at')
                             ->select('inmuebles.*', 'prototipos.nombre')
                             ->offset($pageSize * ($currentPage - 1))
                             ->limit($pageSize)
                             ->get();

            $totalItem = Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                             ->where('prototipos.id_desarrollo', '=', $desarrollo)
                             ->whereNull('inmuebles.deleted_at')
                             ->select('inmuebles.*', 'prototipos.nombre')
                             ->offset($pageSize * ($currentPage - 1))
                             ->limit($pageSize)
                             ->count();
        }

        $allItems = Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                        ->where('prototipos.id_desarrollo', '=', $desarrollo)
                        ->whereNull('inmuebles.deleted_at')
                        ->select('inmuebles.*')
                        ->count();

        //responder a la peticion
        $response = [
            'status' => true,
            'totalItem' => $totalItem,
            'totalPage' => ceil($allItems / $pageSize),
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'data' => $inmuebles,
        ];

        return response()->json($response, 200);
    }

    public function getInmueble($id)
    {
        return response()->json(Inmueble::find($id) , 200);
    }

    public function get($id)
    {
        $inmueble = Inmueble::find($id);
        $prototipo = Prototipo::find($inmueble->prototipo_id);
        $cliente = Cliente::find($inmueble->prospecto_id);

        return response()->json([$inmueble, $prototipo, $cliente], 200);
    }

    public function store(Request $request)
    {
        $inmueble = $request->validate([
            "prototipo_id" => "required|exists:prototipos,id",
            "manzana" => "required|max:255",
            "lote" => "required|max:255",
            "calle" => "required|max:255",
            "numero" => "required|max:255",
            "numero_interior" => "max:255",
            "m2_terreno" => "required|max:255",
            "status" => "required|in:libre,apartado,vencido,titulado",
            "precio" => "required|numeric",
            "medidas_1" => "required|max:255",
            "medidas_2" => "required|max:255",
            "medidas_3" => "required|max:255",
            "medidas_4" => "required|max:255",
            "colindancia_1" => "required|max:255",
            "colindancia_2" => "required|max:255",
            "colindancia_3" => "required|max:255",
            "colindancia_4" => "required|max:255",
            "orientacion_1" => "required|max:255",
            "orientacion_2" => "required|max:255",
            "orientacion_3" => "required|max:255",
            "orientacion_4" => "required|max:255",
        ]);

        Inmueble::create($inmueble);

        return response()->json(['message' => 'Inmueble registrado'], 201);
    }

    public function update(Request $request, $id)
    {
        $inmueble = $request->validate([
            "prototipo_id" => "required|exists:prototipos,id",
            "manzana" => "required|max:255",
            "lote" => "required|max:255",
            "calle" => "required|max:255",
            "numero" => "required|max:255",
            "numero_interior" => "max:255",
            "m2_terreno" => "required|max:255",
            "status" => "required|in:libre,apartado,vencido,titulado",
            "precio" => "required|numeric",
            "medidas_1" => "required|max:255",
            "medidas_2" => "required|max:255",
            "medidas_3" => "required|max:255",
            "medidas_4" => "required|max:255",
            "colindancia_1" => "required|max:255",
            "colindancia_2" => "required|max:255",
            "colindancia_3" => "required|max:255",
            "colindancia_4" => "required|max:255",
            "orientacion_1" => "required|max:255",
            "orientacion_2" => "required|max:255",
            "orientacion_3" => "required|max:255",
            "orientacion_4" => "required|max:255",
        ]);

        Inmueble::findOrFail($id)->update($inmueble);

        return response()->json(['message' => 'Inmueble actualizado'], 200);
    }

    public function destroy($id)
    {
        Inmueble::findOrFail($id)->delete();
        return response()->json(['message' => 'Inmueble eliminado'], 200);
    }

    public function getPoints($desarrollo)
    {
        $points = Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                            ->where('prototipos.id_desarrollo', '=', $desarrollo)
                            ->whereNull('inmuebles.deleted_at')
                            ->select('inmuebles.*')
                            ->get();

        return response()->json($points, 200);
    }

    public function apartar(Request $requesta)
    {
        $data = $request->validate([
            'cliente' => 'required',
            'inmueble' => 'required',
            'usuario' => 'required',
            'empresa' => 'required',
        ]);

        $inmueble = Inmueble::find($data['inmueble']);
        $precio = $inmueble->precio;

        $inmueble->id_vendedor = $data['usuario' ];
        $inmueble->id_prospecto = $data['cliente'];
        $inmueble->fecha_apartado = \Carbon\Carbon::now();
        $inmueble->status = 'apartado';
        $inmueble->precio_venta = $precio;
        $inmueble->save();

        $new_tracking = new Seguimiento;
        $new_tracking->tipo = Seguimiento::TIPO_COMENTARIO;
        $new_tracking->mensaje = 'Inmueble asignado';
        $new_tracking->fecha = \Carbon\Carbon::now();
        $new_tracking->id_usuario = $data['usuario'];
        $new_tracking->id_cliente = $inmueble->id_prospecto;
        $new_tracking->id_empresa = $data['empresa'];
        $new_tracking->save();

        return response()->json(['message' => 'Inmueble apartado'], 200);
    }

    public function cancelarApartado(Request $request)
    {
        $data = $request->validate([
            'cliente' => 'required',
            'inmueble' => 'required',
            'usuario' => 'required',
            'empresa' => 'required',
            'comentario' => 'required'
        ]);

        $inmueble = Inmueble::find($data['inmueble']);
        $old_prospecto_id = $data['cliente'];

        $inmueble->id_vendedor = null;
        $inmueble->id_prospecto = null;
        $inmueble->fecha_apartado = null;
        $inmueble->status = Inmueble::STATUS_LIBRE;
        $inmueble->save();

        // Crea el log en el prospecto
        $new_tracking = new Seguimiento;
        $new_tracking->tipo = Seguimiento::TIPO_COMENTARIO;
        $new_tracking->mensaje = "Lote Cancelado. " . $data['comentario'];
        $new_tracking->fecha = \Carbon\Carbon::now();
        $new_tracking->id_usuario = $data['usuario'];
        $new_tracking->id_cliente = $old_prospecto_id;
        $new_tracking->id_empresa = $data['empresa'];
        $new_tracking->save();

        return response()->json(['message' => 'Apartado cancelado'], 200);
    }

    public function titular(Request $request)
    {
        $data = $request->validate([
            'inmueble' => 'required',
            'usuario' => 'required',
            'empresa' => 'required',
            'cliente' => 'required',
        ]);

        $sembrado = Inmueble::find($data['inmueble']);
        $sembrado->fecha_titulado = \Carbon\Carbon::now();
        $sembrado->status = 'titulado';
        $sembrado->save();

        $new_tracking = new Seguimiento;
        $new_tracking->tipo = Seguimiento::TIPO_COMENTARIO;
        $new_tracking->mensaje = "Inmueble titulado";
        $new_tracking->fecha = \Carbon\Carbon::now();
        $new_tracking->id_usuario = $data['usuario'];
        $new_tracking->id_cliente = $data['cliente'];
        $new_tracking->id_empresa = $data['empresa'];
        $new_tracking->save();

        return response()->json(['message' => 'Inmuebele titulado'], 200);
  	}

    public function cancelarTitular(Request $request)
    {
        $data = $request->validate([
            'inmueble' => 'required',
            'usuario' => 'required',
            'empresa' => 'required',
            'cliente' => 'required',
        ]);

  		$sembrado = Inmueble::find($data['inmueble']);
  		$sembrado->fecha_titulado = null;
  		$sembrado->status = 'apartado';
  		$sembrado->save();

  		$new_tracking = new Seguimiento;
  		$new_tracking->tipo = Seguimiento::TIPO_COMENTARIO;
  		$new_tracking->mensaje = "Titulación cancelada";
  		$new_tracking->fecha = \Carbon\Carbon::now();
  		$new_tracking->id_usuario = $data['usuario'];
  		$new_tracking->id_cliente = $data['cliente'];
        $new_tracking->id_empresa = $data['empresa'];
  		$new_tracking->save();

  		return response()->json(['message' => 'Titulación cancelada'], 200);
  	}
}
