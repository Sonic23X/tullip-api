<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\
{
    User,
    Desarrollo
};

class UserController extends Controller
{
    function getUsers($pageSize, $currentPage, $empresa, $search = null)
    {
        $users = null;

        if ($search == null) {
            if ($currentPage == '1') {
                $users = User::limit($pageSize)
                            ->where('empresa_id', $empresa)
                            ->get();

                $totalItem = User::limit($pageSize)
                                ->where('empresa_id', $empresa)
                                ->count();
            }
            else {
                $users = User::offset($pageSize * ($currentPage - 1))
                            ->limit($pageSize)
                            ->where('empresa_id', $empresa)
                            ->get();

                $totalItem = User::offset($pageSize * ($currentPage - 1))
                            ->limit($pageSize)
                            ->where('empresa_id', $empresa)
                            ->count();
            }

            $response = [
                'status' => true,
                'totalItem' => $totalItem,
                'totalPage' => ceil(User::where('empresa_id', $empresa)->count() / $pageSize),
                'pageSize' => $pageSize,
                'currentPage' => $currentPage,
                'data' => $users
            ];
        }
        else {
            if ($currentPage == '1') {
                $users = User::where('name', 'like', '%'.$search.'%')
                            ->where('empresa_id', $empresa)
                            ->limit($pageSize)
                            ->get();
            }
            else {
                $users = User::where('name', 'like', '%'.$search.'%')
                            ->offset($pageSize * ($currentPage - 1))
                            ->limit($pageSize)
                            ->where('empresa_id', $empresa)
                            ->get();
            }

            $response = [
                'status' => true,
                'totalItem' => User::where('name', 'like', '%'.$search.'%')->count(),
                'totalPage' => ceil(User::where('name', 'like', '%'.$search.'%')->where('empresa_id', $empresa)->count() / $pageSize),
                'pageSize' => $pageSize,
                'currentPage' => $currentPage,
                'data' => $users
            ];
        }

        return response()->json($response);
    }

    public function getUser(Request $request, $id, $empresa )
    {
        return response()->json(
            User::with(array('desarrollos' => function($query) {
                $query->select('nombre');
            }))
            ->where('empresa_id', $empresa)
            ->select('id', 'name', 'email', 'movil', 'observaciones', 'type', 'suspendido')
            ->findOrFail($id), 200);
    }

    function getSellers($pageSize, $currentPage, $empresa)
    {
        $users = null;

        if ($currentPage == '1') {
            $users = User::where('type', 'vendedor')
                        ->where('suspendido', 0)
                        ->where('empresa_id', $empresa)
                        ->limit($pageSize)
                        ->select('id', 'name')
                        ->get();

            $totalItem = User::where('type', 'vendedor')
                            ->where('suspendido', 0)
                            ->where('empresa_id', $empresa)
                            ->limit($pageSize)
                            ->select('id', 'name')
                            ->count();
        }
        else {
            $users = User::where('type', 'vendedor')
                        ->where('suspendido', 0)
                        ->where('empresa_id', $empresa)
                        ->offset($pageSize * ( $currentPage - 1))
                        ->limit($pageSize)
                        ->select('id', 'name')
                        ->get();

            $totalItem = User::where('type', 'vendedor')
                            ->where('suspendido', 0)
                            ->where('empresa_id', $empresa)
                            ->offset($pageSize * ($currentPage - 1))
                            ->limit($pageSize)
                            ->select('id', 'name')
                            ->count();
        }

        $response = [
            'status' => true,
            'totalItem' => $totalItem,
            'totalPage' => ceil(User::where('type', 'vendedor')->where('empresa_id', $empresa)->count() / $pageSize),
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'data' => $users
        ];

        return response()->json($response);
    }

    public function getUserDesarrollo(Request $request, $id, $empresa)
    {
        $user = User::with(array('desarrollos' => function($query) {
            $query->select('nombre');
        }))->findOrFail($id);

        $desarrollos = Desarrollo::where('empresa_id', $empresa)->select('id', 'nombre')->get();

        //respuesta
        $response = [];

        //recorrer los desarrollos
        foreach ($desarrollos as $desarrollo) {
            //check del combo
            $value = 0;

            //recorrer el pivot
            foreach ($user->desarrollos as $userSembrado) {
                //validar si el usuario posee el sembrado
                if ($desarrollo->id == $userSembrado->pivot->desarrollo_id)
                    $value = 1;
            }

            $data = [
                'id' => $desarrollo->id,
                'nombre' => $desarrollo->nombre,
                'checked' => $value
            ];

            //agregar al array de respuesta
            array_push($response, $data);
        }

        return response()->json(['desarrollos' => $response], 201);
    }

    function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'movil' => '',
            'observaciones' => 'max:255',
            'password' => 'required|min:6',
            'type' => 'required|in:vendedor,gerente,superadmin,admin,contabilidad,mesa_control,postventa,telemarketing',
            'empresa_id' => 'required'
        ]);
        
        $input['password'] = bcrypt($input['password']);

        $usuario = User::create($input);

        return response()->json(['message' => 'Usuario registrado con exito'], 201);
    }

    function update(Request $request, $id)
    {
        $dataUser = $request->validate([
            'name'  => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'movil' => '',
            'observaciones' => 'max:255',
            'type' => 'required|in:vendedor,gerente,superadmin,admin,contabilidad,mesa_control,postventa,telemarketing',
        ]);

        $request->validate([
            'desarrollos' => 'array'
        ]);

        //actualizar usuario
        User::where('id', $id)->update($dataUser);

        //buscamos al usuario para actulizar desarrollos
        $user = User::findOrFail($id);

        $desarrollos = $request->input('desarrollos');
        $user->desarrollos()->detach();
        $user->desarrollos()->attach($desarrollos);

        return response()->json(['message' => 'Usuario actualizado con exito'], 200);
    }

    function suspend($id)
    {
        $user = User::findOrFail($id);
        $user->suspendido = !$user->suspendido;
        $user->save();
        $status = $user->suspendido ? 'suspendido' : 'activado';

        return response()->json(['message' => "Usuario {$status} con éxito."], 200);
    }

    function passwordChange(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $input = $request->validate(
        [
            'password' => 'required|min:6|confirmed',
        ]);

        $usuario->password = bcrypt($input['password']);

        $usuario->save();

        return response()->json(['message' => "Contraseña cambiada con éxito."], 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Usuario eliminado'], 200);
    }
}
