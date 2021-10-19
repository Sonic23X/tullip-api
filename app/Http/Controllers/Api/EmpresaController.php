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
        $companyData = $request->validate([
            'nombre' => 'required|min:3|max:255',
            'direccion' => 'required',
            'emailE' => 'required|email',
            'admin' => '',
        ]);

        $companyData['email'] = $companyData['emailE'];
        $companyData['admin'] = 0;
        $companyData['days'] = 30;
    
        $empresa = Empresa::create($companyData);
    
        $userData = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'movil'=> '',
            'observaciones' => 'max:255',
            'password' => 'required|min:6',
        ]);
    
        $userData['password'] = bcrypt($userData['password']);
        $userData['type'] = 'superadmin';
        $userData['empresa_id'] = $empresa->id;
    
        $user = User::create($userData);

        $empresa->admin = $user->id;
        $empresa->save();
    
        return response()->json(['message' => '¡Empresa creada con exito!'], 201);
    }
}
