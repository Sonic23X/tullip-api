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
        $contabilidad = Contabilidad::join('clientes', 'contabilidad.cliente_id', '=', 'clientes.id')
                            ->join('desarrollos', 'clientes.desarrollo_id', '=', 'desarrollos.id')
                            ->where('contabilidad.empresa_id', $idEmpresa)
                            ->where('desarrollos.id', $desarrollo)
                            ->orderBy('contabilidad.id', 'DESC')
                            ->select('contabilidad.fecha_pago', 'clientes.nombre as cliente', 'clientes.hash', 'desarrollos.nombre', 'contabilidad.inmueble_id', 'contabilidad.monto')
                            ->get();

        return response()->json($contabilidad, 200);
    }

    public function store(Request $request)
    {
        $contabilidad = $request->validate([
            'fecha_pago' => 'required',
            'monto' => 'required',
            'inmueble_id' => '',
            'tipo' => 'required',
            'cliente_id' => 'required',
            'empresa_id' => 'required',
        ]);

        Contabilidad::create($contabilidad);

        return response()->json(['message' => 'Pago registrado'], 201);
    }

    public function autocompletadoClientes($idEmpresa, $desarrollo)
    {
        $cliente = Cliente::where('empresa_id', $idEmpresa)
                        ->where('desarrollo_id', $desarrollo)
                        ->select('id', 'nombre')
                        ->get();

        return response()->json($cliente, 200);
    }

    public function getClienteInmuebles($id)
    {
        $inmuebles = Inmueble::join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                            ->join('desarrollos', 'prototipos.desarrollo_id', '=', 'desarrollos.id')
                            ->where('inmuebles.prospecto_id', $id)
                            ->select('inmuebles.id as idInmueble', 'desarrollos.nombre as desarrollo', 'inmuebles.numero')
                            ->get();

        return response()->json($inmuebles, 200);
    }
}
