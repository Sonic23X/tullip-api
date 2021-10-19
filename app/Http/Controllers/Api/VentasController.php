<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    Inmueble,
    Contabilidad
};

class VentasController extends Controller
{
    public function getVentas( $idEmpresa, $desarrollo )
    {
        $sembrados = Inmueble::join('clientes', 'inmuebles.id_prospecto', '=', 'clientes.id')
                            ->join('prototipos', 'inmuebles.id_prototipo', '=', 'prototipos.id')
                            ->where('prototipos.id_empresa', $idEmpresa)
                            ->where('prototipos.id_desarrollo', $desarrollo)
                            ->select('clientes.id', 'clientes.nombre', 'clientes.hash', 'inmuebles.manzana', 'inmuebles.lote', 'inmuebles.calle', 'inmuebles.numero', 'inmuebles.precio', 'inmuebles.precio_venta')
                            ->get();
        $data = [];
        foreach ($sembrados as $sembrado) {
            $cliente = $sembrado->id;
            $pagos = Contabilidad::where('id_cliente', $cliente)->get();

            if ($pagos->count() > 0) {
                $total = 0;
                foreach ($pagos as $pago) {
                    $total += $pago->monto;
                }

                $sembrado['pagos'] = $pagos->count();
                $sembrado['monto_total'] = $total;
                array_push($data, $sembrado);
            }
            else {
                $sembrado['pagos'] = 0;
                $sembrado['monto_total'] = 0;
                array_push($data, $sembrado);
            }
        }
        return response()->json($data, 200);
    }
}
