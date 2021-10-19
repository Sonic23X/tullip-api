<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    Contabilidad,
    Desarrollo,
    Inmueble,
    Cliente
};

class ContabilidadController extends Controller
{
    public function getAll($idEmpresa, $desarrollo)
    {
        $contabilidad = Contabilidad::join('clientes', 'contabilidad.id_cliente', '=', 'clientes.id')
                            ->join('desarrollos', 'clientes.id_desarrollo', '=', 'desarrollos.id')
                            ->where('contabilidad.id_empresa', $idEmpresa)
                            ->where('desarrollos.id', $desarrollo)
                            ->orderBy('contabilidad.id', 'DESC')
                            ->select('contabilidad.fecha_pago', 'clientes.nombre as cliente', 'clientes.hash', 'desarrollos.nombre', 'contabilidad.id_inmueble', 'contabilidad.monto')
                            ->get();

        return response()->json($contabilidad, 200);
    }

    public function store(Request $request)
    {
        $contabilidad = $request->validate([
            'fecha_pago' => 'required',
            'monto' => 'required',
            'id_inmueble' => '',
            'tipo' => 'required',
            'id_cliente' => 'required',
            'id_empresa' => 'required',
        ]);

        Contabilidad::create($contabilidad);

        return response()->json(['message' => 'Pago registrado'], 201);
    }

    public function autocompletadoClientes($idEmpresa, $desarrollo)
    {
        $cliente = Cliente::where('id_empresa', $idEmpresa)
                        ->where('id_desarrollo', $desarrollo)
                        ->select('id', 'nombre')
                        ->get();

        return response()->json($cliente, 200);
    }

    public function getClienteInmuebles($id)
    {
        $inmuebles = Inmueble::join('prototipos', 'inmuebles.id_prototipo', '=', 'prototipos.id')
                            ->join('desarrollos', 'prototipos.id_desarrollo', '=', 'desarrollos.id')
                            ->where('inmuebles.id_prospecto', $id)
                            ->select('inmuebles.id as idInmueble', 'desarrollos.nombre as desarrollo', 'inmuebles.numero')
                            ->get();

        return response()->json($inmuebles, 200);
    }
}
