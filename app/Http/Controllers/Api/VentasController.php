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
        $sembrados = Inmueble::join('clientes', 'inmuebles.prospecto_id', '=', 'clientes.id')
                            ->join('prototipos', 'inmuebles.prototipo_id', '=', 'prototipos.id')
                            ->where('prototipos.empresa_id', $idEmpresa)
                            ->where('prototipos.desarrollo_id', $desarrollo)
                            ->select('clientes.id', 'clientes.nombre', 'clientes.hash', 'inmuebles.manzana', 'inmuebles.lote', 'inmuebles.calle', 'inmuebles.numero', 'inmuebles.precio', 'inmuebles.precio_venta')
                            ->get();
        $data = [];
        foreach ($sembrados as $sembrado) {
            $cliente = $sembrado->id;
            $pagos = Contabilidad::where('cliente_id', $cliente)->get();

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
