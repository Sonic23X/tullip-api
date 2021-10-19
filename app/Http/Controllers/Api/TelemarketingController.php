<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Cliente,
    Seguimiento
};

class TelemarketingController extends Controller
{
    public function store(Request $request, $userId)
    {
        $data = $request->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'telefono_casa' => 'max:15',
            'telefono_celular' => 'max:15|required',
            'email' => 'nullable|email',
            'curp' => 'max:255',
            'como_se_entero' => 'max:255',
            'nss' => '',
            'fecha_contacto' => '',
            'usuario_facebook' => '',
            'usuario_instagram' => '',
            'usuario_twitter' => '',
            'fecha_nacimiento'=>''
        ]);

        $new_prospect = new Cliente;
        $new_prospect->nombre = $data['nombre'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'];
        $new_prospect->anexo_detalles = $data;
        $new_prospect->id_usuario = $userId;
        $new_prospect->id_desarrollo = $request->input('fraccionamiento_id');
        $new_prospect->hash = str_random(10);
        $new_prospect->completado = 0;
        $new_prospect->referencia_bancaria = '';
        $new_prospect->condicion_telemarketing = 'lead';
        $new_prospect->telemarketing = 1;
        $new_prospect->condicion_changed = \Carbon\Carbon::now();
        $new_prospect->id_empresa = $request->input('empresa');
        $new_prospect->save();

        if($new_prospect->id)
        {
            $new_tracking = new Seguimiento;
            $new_tracking->tipo = 'nuevo';
            $new_tracking->mensaje = '';
            $new_tracking->id_usuario =  $userId;
            $new_tracking->fecha = \Carbon\Carbon::now();
            $new_tracking->id_empresa = $request->input('empresa');
            $new_prospect->trackings()->save($new_tracking);
        }

        return response()->json(['message' => '¡Registro creado!'], 201);
    }

    public function update(Request $request)
    {
        $prospect = Cliente::where('id', $request->input('cardId'))->first();

        if (empty($prospect))
            return $this->response->json(['message' => 'error to find a prospect'], 404);

        $prospect->condicion =$request->input('targetLaneId');
        $prospect->condicion_changed = \Carbon\Carbon::now();

        $prospect->save();

        return response()->json(['message' => '¡Registro actualizado!'], 200);
    }

    public function getClientes(Request $request, $empresa)
    {
        $clientes_prospectos = Cliente::join('desarrollos', 'clientes.id_desarrollo', '=', 'desarrollos.id')
            ->where('clientes.condicion', 'prospecto')
            ->where('clientes.telemarketing',1)
            ->where('clientes.id_empresa', $empresa)
            ->select('clientes.id','clientes.nombre as title','clientes.condicion as laneId','clientes.telemarketing', 'desarrollos.nombre as description')
            ->get();

        $clientes_calificados = Cliente::join('desarrollos', 'clientes.id_desarrollo', '=', 'desarrollos.id')
            ->where('clientes.condicion', 'calificado')
            ->where('clientes.telemarketing',1)
            ->where('clientes.id_empresa', $empresa )
            ->select('clientes.id','clientes.nombre as title','clientes.condicion as laneId','clientes.telemarketing', 'desarrollos.nombre as description')
            ->get();

        $clientes_citados = Cliente::join('desarrollos', 'clientes.id_desarrollo', '=', 'desarrollos.id')
            ->where('clientes.condicion', 'cita')
            ->where('clientes.telemarketing',1)
            ->where('clientes.id_empresa', $empresa )
            ->select('clientes.id','clientes.nombre as title','clientes.condicion as laneId','clientes.telemarketing', 'desarrollos.nombre as description')
            ->get();

        $clientes_cerrados = Cliente::join('desarrollos', 'clientes.id_desarrollo', '=', 'desarrollos.id')
            ->where('clientes.condicion', 'cierre')
            ->where('clientes.telemarketing',1)
            ->where('clientes.id_empresa', $empresa )
            ->select('clientes.id','clientes.nombre as title','clientes.condicion as laneId','clientes.telemarketing', 'desarrollos.nombre as description')
            ->get();

        $clientes_json = [];
        $clientes_json['prospectos'] = $clientes_prospectos;
        $clientes_json['calificados'] = $clientes_calificados;
        $clientes_json['citados'] = $clientes_citados;
        $clientes_json['cerrados'] = $clientes_cerrados;
        $clientes_json['prospectos_count'] = $clientes_prospectos->count();
        $clientes_json['calificados_count'] = $clientes_calificados->count();
        $clientes_json['citados_count'] = $clientes_citados->count();
        $clientes_json['cerrados_count'] = $clientes_cerrados->count();

        return response()->json($clientes_json, 200);
    }
}
