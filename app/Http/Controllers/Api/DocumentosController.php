<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    Documento,
    Cliente
};

class DocumentosController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $documento = Documento::findOrFail($id);
        ob_clean();

        return $this->response->file(storage_path('app/'.$documento->path));
    }

    public function getDocumentsByClient($hash)
    {
        $prospect = Cliente::where('hash', $hash)->first();
        if (empty($prospect)) 
            return response()->json(['message' => 'Cliente no encontrado'], 400);
        
        return $prospect->documentos;
    }
}
