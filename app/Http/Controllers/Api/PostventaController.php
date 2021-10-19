<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    Desarrollo,
    Inmueble,
    TicketPostVenta,
    User
};

class PostventaController extends Controller
{
    public function getPostVentas(Desarrollo $desarrollo)
    {
        $inmuebles= $desarrollo->inmueblesTitulados()->load('cliente','vendedor','tickets');
        return response()->json($inmuebles);
    }

    public function show(Request $request, $id_inmueble)
    {
        $inmueble = Inmueble::FindOrFail($id_inmueble)->load('cliente','vendedor','tickets');
        return response()->json($inmueble);
    }

    public function storeCita(Request $request, $id_inmueble)
    {
        $request->validate([
            "fecha" => 'required|date'
        ]);

        $inmueble = Inmueble::findOrFail($id_inmueble);

        $citas = $inmueble->citas_entrega;
        $fecha = \Carbon\Carbon::parse($request->input('fecha'));

        data_set($citas, 'citas.*.cancelada',1);
        array_push($citas['citas'], [
            'fecha' => $fecha->format('Y-m-d H:i'),
            'cancelada' => 0
        ]);

        $inmueble->citas_entrega = $citas;
        $inmueble->save();

        return response()->json($inmueble->citas_entrega);
    }

    public function storeTicket(Request $request, $id_inmueble)
    {
        $inmueble = Inmueble::findOrFail($id_inmueble);
        $detalles_request = json_decode($request->input('detalles'));

        $ticket = new TicketPostVenta();
        $ticket->id_inmueble = $inmueble->id;

        $detalles['detalles'] = [];

        foreach ($detalles_request as $key => $item) {
            $path='';

            if ($request->hasFile("file[{$item->id}]")) {
                $file = $request->file("file[{$item->id}]");
                $name = "ticket_$ticket->id_detalle_$key.".$file->getClientOriginalExtension();
                $path = "documents/inmueble/{$inmueble->id}";
                $file->storeAs("public/{$path}", $name);
            }

            $array = [     
                "descripcion" => $item->nombre,
                "evidencia_url" => "storage/".$path,
                "concluido" => 0,
                "citas" => []
            ];

            array_push($detalles['detalles'],$array);
        }

        $ticket->detalles = $detalles;
        $ticket->observaciones = $request->input('observaciones');

        $ticket->save();

        return response()->json(['message' => 'Ticket creado correctamente'], 201);
    }

    public function updateTicket(Request $request, $ticket_id)
    {
        $ticket = TicketPostVenta::findOrFail($ticket_id);

        if ($request->input('detalles')) {
            $detalle = $ticket->detalles['detalles'];
            $ticket->detalles = ['detalles' => $request->input('detalles')];
        }

        $ticket->save();

        return response()->json(['message' => 'Ticket guardado correctamente'], 201);
    }

    public function entregaInmueble(Request $request, $id_inmueble)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $inmueble = Inmueble::findOrFail($id_inmueble);
        $inmueble->entrega = 1;
        $inmueble->fecha_entrega = $request->input('fecha');
        $documentos['documentos'] = $inmueble->documentos['documentos'] ?? [];

        if ($request->documentos) {
            foreach ($request->documentos as $file) {
                $name = $file->getClientOriginalName();
                $path = $file->storeAs("documento/inmueble/$inmueble->id/entrega",$name);
                $documento = [
                    'nombre' => $name,
                    'url' => $path
                ];

                array_push($documentos['documentos'],$documento);
            }
            $inmueble->documentos = $documentos;
        }
        $inmueble->save();

        return response()->json(['message' => 'Inmueble entregado'], 201);
    }

    public function cerrarTicket(Request $request,$id_inmueble)
    {
        $request->validate([
            'reporte_file' => 'required|mimes:pdf,jpg,png,jpeg',
        ]);

        $ticket = TicketPostVenta::where('inmueble_id', $id_inmueble)
                    ->where('id', $request->input('ticket_id'))
                    ->first();
        $status = '';

        $detallesAbiertos = array_search('0', array_column($ticket->detalles['detalles'], 'concluido'));
        if (($detallesAbiertos === null) || ($detallesAbiertos === false))
            $detallesAbiertos = false;

        if (($detallesAbiertos === false) && ($request->hasFile('reporte_file'))) {
            $ticket->estatus = TicketPostVenta::ESTATUS['CERRADO'];
            $ticket->fecha_conclusion = \Carbon\Carbon::today();

            $file = $request->file('reporte_file');

            $nombre = 'reporte_ticket_'.$ticket->id.'.'. $file->getClientOriginalExtension();
            $path = "/documents/inmueble/{$id_inmueble}/";
            $file->storeAs("/public".$path,$nombre);

            $ticket->reporte_url ="storage". $path.$nombre;

            $ticket->save();

            return response()->json(['message' => 'Ticket Cerrado'], 201);
        }

        return response()->json(['message' => 'Aún hay tickets abierto'], 409);
    }

    public function storeFile(Request $request,$id_inmueble)
    {
        $inmueble = Inmueble::findOrFail($id_inmueble);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $documentos['documentos'] = $inmueble->documentos['documentos'] ?? [];
            $name = $file->getClientOriginalName();
            $path = "documents/inmueble/$inmueble->id/entrega";
            $file->storeAs("public/".$path, $name);

            $documento = [
                'nombre' => $name,
                'url' => "storage/".$path."/".$name
            ];

            array_push($documentos['documentos'],$documento);

            $inmueble->documentos = $documentos;
            $inmueble->save();
            return response()->json(['message' => 'Documento Cargado'], 201);
        }

        return response()->json(['message' => 'Error al subir el documento'], 400);
    }

}
