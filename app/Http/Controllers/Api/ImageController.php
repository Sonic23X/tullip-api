<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function getMap($name)
    {
        return Storage::download('mapas/'. $name);
    }

    public function getPrototype($id, $name)
    {
        return Storage::download('public/prototipos/'.$id .'//fotos/' . $name);
    }

    public function getDocument(Request $request, $id, $name)
    {
        return Storage::download('public/documents/inmueble/'.$id.'/' . $name);
    }
}
