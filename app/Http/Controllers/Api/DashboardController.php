<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;

class DashboardController extends Controller
{
    public function getClientes( Request $request, $empresa )
    {
        $clientes_prospectos = Cliente::join('desarrollos', 'clientes.desarrollo_id', '=', 'desarrollos.id')
                                    ->where('clientes.condicion', 'prospecto')
                                    ->where('clientes.telemarketing',1)
                                    ->where('clientes.empresa_id', $empresa)
                                    ->select('clientes.id', 'clientes.nombre as title', 'clientes.condicion as laneId', 'clientes.telemarketing', 'desarrollos.nombre as description')
                                    ->orderBy('id', 'DESC')
                                    ->offset(0)
                                    ->limit(8)
                                    ->get();

        $clientes_calificados = Cliente::join('desarrollos', 'clientes.desarrollo_id', '=', 'desarrollos.id')
                                    ->where('clientes.condicion', 'calificado')
                                    ->where('clientes.telemarketing',1)
                                    ->where('clientes.empresa_id', $empresa)
                                    ->select('clientes.id', 'clientes.nombre as title', 'clientes.condicion as laneId', 'clientes.telemarketing', 'desarrollos.nombre as description')
                                    ->orderBy('id', 'DESC')
                                    ->offset(0)
                                    ->limit(8)
                                    ->get();

        $clientes_citados = Cliente::join('desarrollos', 'clientes.desarrollo_id', '=', 'desarrollos.id')
                                ->where('clientes.condicion', 'cita')
                                ->where('clientes.telemarketing',1)
                                ->where('clientes.empresa_id', $empresa)
                                ->select('clientes.id', 'clientes.nombre as title', 'clientes.condicion as laneId', 'clientes.telemarketing', 'desarrollos.nombre as description')
                                ->orderBy('id', 'DESC')
                                ->offset(0)
                                ->limit(8)
                                ->get();

        $clientes_cerrados = Cliente::join('desarrollos', 'clientes.desarrollo_id', '=', 'desarrollos.id')
                                ->where('clientes.condicion', 'cierre')
                                ->where('clientes.telemarketing',1)
                                ->where('clientes.empresa_id', $empresa)
                                ->select('clientes.id', 'clientes.nombre as title', 'clientes.condicion as laneId', 'clientes.telemarketing', 'desarrollos.nombre as description')
                                ->orderBy('id', 'DESC')
                                ->offset(0)
                                ->limit(8)
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
        $clientes_json['monto_cerrados_count'] = '0';

        return response()->json($clientes_json, 200);
    }

    public function update( Request $request)
    {
        $prospect = Cliente::where('id', $request->input('cardId'))->first();

        if (empty($prospect))
            return $this->response->json(['message' => 'error to find a prospect'], 404);

        $prospect->condicion =$request->input('targetLaneId');
        $prospect->condicion_changed = \Carbon\Carbon::now();

        $prospect->save();

        return response()->json(['message' => 'updated!'], 200);
    }
}
