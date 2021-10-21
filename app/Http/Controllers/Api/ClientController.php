<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models;

class ClientController extends Controller
{
    //methods of clients
    public function all(Request $request)
    {
        return Models\Cliente::orderBy('id', 'DESC')
                    ->get()
                    ->load(array('trackings' => function($query)
                        {
                            $query->orderBy('created_at','DESC');
                        })
                    );
    }
    
    public function getClients($id_desarrollo, $pageSize, $currentPage, Request $request)
    {
        $clients = Models\Cliente::where('desarrollo_id', $id_desarrollo)->orderBy('created_at', 'desc');
        if ($request->input('nombre')) {
            $clients->where('nombre', 'like', "%{$request->input('nombre')}%");
        }
        if ($request->input('vendedor')) {
            $clients->where('user_id', $request->input('vendedor'));
        }
        $totalItem = $clients->count();
        $currentPage=$currentPage + 1;
        if ($currentPage == '1')
            $clients = $clients->limit($pageSize)->get();
        else
            $clients = $clients->offset($pageSize * ($currentPage - 1))->limit($pageSize)->get();

        $response = [
            'status' => true,
            'totalItem' => $totalItem,
            'totalPage' => round($totalItem / $pageSize),
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'data' => $clients->load('seller')
        ];
        return response()->json($response);
    }

    public function get($hash)
    {
        return Models\Cliente::where('hash', $hash)->first()->load('lotes');
    }

    public function store(Request $request)
    {
        $data = Validator::make($request->all(),
        [
            'user_id'=>'required',
            'desarrollo' => 'required',
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'telefono_casa' => 'max:255',
            'telefono_celular' => 'max:255',
            'email' => 'max:255',
            'curp' => 'max:255',
            'fecha_nacimiento' => '',
            'como_se_entero' => 'max:255',
            'nss' => '',
            'genero' => '',
            'estado_civil' => '',
            'regimen_patrimonial' => '',
            'calle' => '',
            'colonia' => '',
            'entidad' => '',
            'municipio' => '',
            'codigo_postal' => '',
            'telefono_casa_clave' => '',
            'tc' => '',
            'tc_direccion' => '',
            'rfc' => '',
            'usuario_facebook' => '',
            'usuario_twitter' => '',
            'usuario_instagram' => '',
            'empresa' => ''
        ])->validate();

        $new_prospect = new Models\Cliente;
        $new_prospect->nombre = $data['nombre'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'];
        $new_prospect->anexo_detalles = $data;
        $new_prospect->user_id = $data['user_id'];
        $new_prospect->desarrollo_id = $data['desarrollo'];
        $new_prospect->hash = Str::random(10);
        $new_prospect->completado = 0;
        $new_prospect->referencia_bancaria = '';
        $new_prospect->condicion_changed = \Carbon\Carbon::now();
        $new_prospect->empresa_id = $data['empresa'];
        $new_prospect->save();

        if ($new_prospect->id) {
            $new_tracking = new Models\Seguimiento;
            $new_tracking->tipo = 'nuevo';
            $new_tracking->mensaje = 'Cliente registrado';
            $new_tracking->user_id =  $data['user_id'];
            $new_tracking->empresa_id = $data['empresa'];
            $new_tracking->fecha = \Carbon\Carbon::now();
            $new_prospect->trackings()->save($new_tracking);

            $new_prospect->setCompletedPercent();

            return response()->json(['message' => 'cliente creado'], 201);
        }
        else
            return response()->json(['message' => 'error al crear el cliente'], 400);
    }

    public function edit(Request $request, $hash, $id_panel) 
    {
        $prospect = Models\Cliente::where('hash', $hash)->first();
        if (empty($prospect)) {
            return response()->json(['message' => 'error al crear el cliente'], 400);
        }
        switch ($id_panel) {
            case 'datosPersonales':
                $data = Validator::make($request->all(), [
                    'desarrollo' => 'required',
                    'nombre' => 'required',
                    'apellido_paterno' => 'required',
                    'apellido_materno' => 'required',
                    'telefono_casa' => 'max:255',
                    'telefono_celular' => 'max:255',
                    'email' => 'max:255',
                    'curp' => 'max:255',
                    'fecha_nacimiento' => '',
                    'como_se_entero' => 'max:255',
                    'nss' => '',
                    'genero' => '',
                    'estado_civil' => '',
                    'regimen_patrimonial' => '',
                    'calle' => '',
                    'colonia' => '',
                    'entidad' => '',
                    'municipio' => '',
                    'codigo_postal' => '',
                    'telefono_casa_clave' => '',
                    'tc' => '',
                    'tc_direccion' => '',
                    'rfc' => '',
                    'usuario_facebook' => '',
                    'usuario_twitter' => '',
                    'usuario_instagram' => ''
                ])->validate();

                $prospect->nombre = $data['nombre'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'];
                $prospect->anexo_detalles = $data;
                $prospect->save();
                $prospect->setCompletedPercent();

                return response()->json(['message' => 'cliente guardado'], 201);
                break;

            case 'datosCredito':
                $data = Validator::make($request->all(), [
                    'credito' => ''
                ])->validate();

                $prospect->anexo_credito = $data['credito'];
                $prospect->save();
                $prospect->setCompletedPercent();

                return response()->json(['message' => 'cliente guardado'], 201);
                break;

            case 'datosLaborales':
                $data = Validator::make($request->all(), [
                    'nombre_empresa' => '',
                    'direccion_empresa' => '',
                    'telefono' => '',
                    'horario' => '',
                    'rfc' => '',
                    'ext' => '',
                    'url' => '',
                    'nrp' => ''
                ])->validate();

                $prospect->anexo_trabajo = $data;
                $prospect->save();
                $prospect->setCompletedPercent();
                
                return response()->json(['message' => 'cliente editado'], 201);
                break;

            case 'referencias':
                $data = Validator::make($request->all(), [
                    'ref1_nombre' => '',
                    'ref1_apellido_paterno' => '',
                    'ref1_apellido_materno' => '',
                    'ref1_telefono' => '',
                    'ref1_celular' => '',
                    'ref2_nombre' => '',
                    'ref2_apellido_paterno' => '',
                    'ref2_apellido_materno' => '',
                    'ref2_telefono' => '',
                    'ref2_celular' => ''
                ])->validate();

                $prospect->anexo_referencias = $data;
                $prospect->save();
                $prospect->setCompletedPercent();
                
                return response()->json(['message' => 'cliente guardado'], 201);
                break;

            case 'datosConyuge':
                $data = Validator::make($request->all(), [
                    'nombre' => '',
                    'apellido_paterno' => '',
                    'apellido_materno' => '',
                    'nss' => '',
                    'curp' => '',
                    'rfc' => '',
                    'telefono_casa' => '',
                    'telefono_trabajo' => '',
                    'telefono_celular' => '',
                    'correo_electronico'=> '',
                    'escolaridad' => '',
                    'horario_trabajo' => '',
                    'is_mixto' => '',
                    'empresa' => '',
                    'nrp' => ''
                ])->validate();

                $prospect->anexo_conyuge = $data;
                $prospect->save();

                return response()->json(['message' => 'cliente guardado'], 201);
                break;

            case '_status':
                $prospect->status = $request->input('status');
                $prospect->save();
                break;

            case '_condicion':
                $prospect->condicion = $request->input('condicion');
                $prospect->condicion_changed = \Carbon\Carbon::now();
                $prospect->save();
                break;

            case '_validacion':
                $prospect->validado = $request->input('validado');
                $prospect->save();
                break;

            case '_vendedor':
                $prospect->user_id = $request->input('vendedor');
                $prospect->save();
                
                return 	response()->json(['message' => 'Vendedor editado'], 201);
                break;
        }
    }

    public function delete($hash)
    {
        $prospect = Models\Cliente::where('hash', $hash)->first();
        $prospect->delete();

        return response()->json(['message' => 'client deleted'], 200);
    }

    //seguimientos
    public function getSeguimiento()
    {
        return Models\Seguimiento::orderBy('created_at','DESC')->get();
    }

    public function getSeguimientoByClient($hash,$id_user)
    {
        $prospect = Models\Cliente::where('hash', $hash)->first();

        $seguimento = Models\Seguimiento::where('cliente_id', $prospect->id)
                        ->where('user_id', $id_user)
                        ->where('tipo', '!=', 'tarea')
                        ->orderBy('created_at', 'DESC')
                        ->get();
        return $seguimento->load('seller');
    }

    public function getSeguimientoByUser($id_user)
    {
        $seguimento = Models\Seguimiento::where('user_id',$id_user)
                        ->where('tipo','!=','tarea')
                        ->orderBy('created_at','DESC')
                        ->limit(10)
                        ->get();
        return $seguimento->load( 'seller', 'cliente' );
    }

    public function addSeguimiento(Request $request, $hash, $id_user)
    {
        $prospect = Models\Cliente::where('hash', $hash)->first();

        if (empty($prospect)) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        
        $tracking = $request->only('date','message','type', 'empresa');

        if ($tracking) {
            $new_tracking = new Models\Seguimiento;
            $new_tracking->tipo = $tracking['type'];
            $new_tracking->mensaje = $tracking['message'];

            if ($tracking['date']) {
                $fecha = new \Carbon\Carbon($tracking['date']);
                $fecha->tz = 'UTC';
                $new_tracking->fecha = $fecha;
            }

            $new_tracking->user_id = $id_user;
            $new_tracking->empresa_id = $tracking['empresa'];
            $prospect->trackings()->save($new_tracking);

            return response()->json(['message' => 'seguimiento creado'], 201);
        }
    }

    //pendientes
    public function getTasks($hash, $id_user)
    {
        $customer = Models\Cliente::where('hash', $hash)->first();
        $tracking = $customer->trackings()
                        ->where('tipo', 'tarea')
                        ->where('user_id', $id_user)
                        ->orderBy('created_at','DESC')
                        ->get();
        return $tracking->load('cliente');
    }

    public function getTasksByUser($id_user)
    {
        $trackings = Models\Seguimiento::where('tipo', 'tarea')
                        ->where('user_id', $id_user)
                        ->where('completado', null )
                        ->orderBy('created_at','DESC')
                        ->get();

        return $trackings->load('cliente');
    }

    public function getTasksByAdmin( $empresa )
    {
        $trackings = Models\Seguimiento::where('tipo', 'tarea')
                        ->where('empresa_id', $empresa)
                        ->where('completado', null)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return $trackings->load('cliente');
    }

    public function storeTask(Request $request, $hash, $id_user)
    {
        $this->validate($request,
        [
            'mensaje' => 'required'
        ]);

        $customer = Models\Cliente::where('hash', $hash)->first();

        $task = new Models\Seguimiento;
        $task->tipo = 'tarea';
        $task->mensaje = $request->get('mensaje');
        $task->user_id = $id_user;
        $task->empresa_id = $request->input('empresa');
        $task->fecha = $request->input('fecha');

        $customer->trackings()->save($task);

        return response()->json(['message' => 'Pendiente creado'], 201);
    }

    public function updateTask(Request $request, $hash, $taskId)
    {
        $this->validate($request,
        [
            'open' => 'required'
        ]);

        $customer = Models\Cliente::where('hash', $hash)->first();

        $task = $customer->trackings()->where('tipo', 'tarea')->where('id', $taskId)->first();
        $task->completado = $request->get('open');

        $task->save();

        return response()->json(['message' => 'Pendiente completado'], 201);
    }

    public function completeTask(Request $request)
    {
        $this->validate($request,
        [
            'task' => 'required'
        ]);

        $tareas = $request->get('task');

        foreach ($tareas as $tarea) 
        {
            Models\Seguimiento::where('id', $tarea)->update(['completado' => 1]);
        }

        return response()->json(['message' => 'Pendiente(s) completado(s)'], 200);
    }

    public function deleteTask(Request $request, $hash, $taskId, $id_user)
    {
        $customer = Models\Cliente::where('hash', $hash)->first();

        $task = $customer->trackings()->where('tipo', 'tarea')->where('id', $taskId)->first();

        if ($task->user_id != $id_user)
            return response('', 403);

        $task->delete();

        return response()->json(['message' => 'Pendiente eliminado'], 201);
    }

    public function storeDoc(Request $request, $hash,  $id_user)
    {
        $prospect = Models\Cliente::where('hash', $hash)->first();
        if (empty($prospect))
            return response()->json(['message' => 'error al crear el cliente'], 400);
        
        $valid_extensions = array('jpg','jpeg', 'png', 'bmp', 'gif');
        $file = $request->file('documento');

        if ($request->hasFile('documento')) {

            $extension = $file->getClientOriginalExtension();
            if (Arr::exists($valid_extensions,$extension)) 
                return response()->json(['message' => 'Extensión del archivo inválida'], 400);

            $path = $file->storeAs("documents/{$prospect->id}", $request->input('nombre').'.'.$extension);

            $documentData = [
                'cliente_id' => $prospect->id,
                'user_id' => $id_user,
                'nombre' => $request->input('nombre'),
                'path'=> $path,
                'empresa_id'=> $request->input('empresa')
            ];

            $document = Models\Documento::create($documentData);
            
            return response()->json(['message' => 'Documento guardado'], 201);
        }
        return response()->json(['message' => 'Documento no válido'], 400);
    }

    public function searchClientData(Request $request, $userID, $desarrollo)
    {
        //buscamos el usuario
        $user = User::find($userID);

        //obtenemos clientes segun tipo
        switch ($user->type)
        {
            case 'vendedor':
                $cliente = Models\Cliente::where('validado', 1)
                                ->where('user_id', $user->id)
                                ->where('desarrollo_id', $desarrollo)
                                ->select('id', 'nombre')
                                ->get();

                return response()->json($cliente, 200);
                break;
            case 'admin':
                $cliente = Models\Cliente::where('validado', 1)
                                        ->where('desarrollo_id', $desarrollo)
                                        ->select('id', 'nombre')
                                        ->get();

                return response()->json( $cliente, 200 );
                break;
            case 'superadmin':
                $cliente = Models\Cliente::where('validado', 1)
                                        ->where('desarrollo_id', $desarrollo)
                                        ->select('id', 'nombre')
                                        ->get();
                return response()->json( $cliente, 200 );
                break;
            default:
                return response()->json('Error al obtener el cliente', 403);
                break;
        }
    }
}
