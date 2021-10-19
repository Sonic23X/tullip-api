<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    Empresa,
    User
};

class EmpresaController extends Controller
{
    public function getEmpresa(Request $request, $id)
    {
        return response()->json(Empresa::find($id), 200);
    }

    public function getAll(Request $request)
    {
        return response()->json(Empresa::all(), 200);
    }

    public function store(Request $request)
    {
        $inputEmpresa = $request->validate([
            'nombre' => 'required|min:3|max:255',
            'direccion' => 'required',
            'correo' => 'required',
        ]);
    
        $inputEmpresa['email'] = $inputEmpresa['correo'];
    
        $empresa = Empresa::create($inputEmpresa);
    
        $inputUser = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'movil'=> '',
            'observaciones' => 'max:255',
            'password' => 'required|min:6',
        ]);
    
        $inputUser['password'] = bcrypt($inputUser['password']);
        $inputUser['type'] = 'superadmin';
        $inputUser['id_empresa'] = $empresa->id;
    
        User::create($inputUser);
    
        return response()->json(['message' => '¡Empresa creada con exito!'], 201);
    }
}
