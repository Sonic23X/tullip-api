<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AccessController,
    ChatbotController,
    ClientController,
    ContabilidadController,
    DashboardController,
    DesarrollosController,
    DocumentosController,
    EmpresaController,
    ImageController,
    InmuebleController,
    PostventaController,
    PrototipoController,
    TelemarketingController,
    UserController,
    VentasController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AccessController::class, 'login'])->name("login");

Route::middleware('auth:api')->group(function() {
    //clients
    Route::get('/clients/all', [ClientController::class, 'all']);
    Route::get('/clients/{hash}', [ClientController::class, 'get']);
    Route::post('/clients/save', [ClientController::class, 'store']);
    Route::delete('/clients/delete/{hash}', [ClientController::class, 'delete']);
    Route::post('/clients/{hash}/edit/{id_panel}', [ClientController::class, 'edit']);
    Route::post('/clients/{hash}/documentos/{id_user}', [ClientController::class, 'storeDoc']);
    Route::post('clients/{id_desarrollo}/{pageSize}/{currentPage}', [ClientController::class, 'getClients']);
    Route::get( 'clients/search/{usuario}/{desarrollo}', [ClientController::class, 'searchClientData']);

    //tasks
    Route::post('/tasks/pendientes/complete', [ClientController::class, 'completeTask']);
    Route::get('/tasks/pendientes/all/{id_empresa}', [ClientController::class, 'getTasksByAdmin']);
    Route::get('/tasks/pendientes/{hash}/{id_user}', [ClientController::class, 'getTasks']);
    Route::get('/tasks/pendientes/{id_user}', [ClientController::class, 'getTasksByUser']);
    Route::post('/tasks/pendientes/save/{hash}/{id_user}', [ClientController::class, 'storeTask']);
    Route::put('/tasks/pendientes/update/{hash}/{taskId}', [ClientController::class, 'updateTask']);
    Route::delete('/tasks/pendientes/delete/{hash}/{taskId}/{id_user}', [ClientController::class, 'deleteTask']);

    //seguimientos
    Route::get('/seguimientos/all', [ClientController::class, 'getSeguimiento']);
    Route::get('/seguimientos/{id_user}', [ClientController::class, 'getSeguimientoByUser']);
    Route::get('/seguimientos/{hash}/{id_user}', [ClientController::class, 'getSeguimientoByClient']);
    Route::post('/seguimientos/new/{hash}/{id_user}', [ClientController::class, 'addSeguimiento']);

    //ventas
    Route::get('/ventas/all/{id}/{desarrollo}', [VentasController::class, 'getVentas']);

    //contabilidad
    Route::get('/contabilidad/all/{id}/{desarrollo}', [ContabilidadController::class, 'getAll']);
    Route::get('/contabilidad/autocompletado/{id}/{desarrollo}', [ContabilidadController::class, 'autocompletadoClientes']);
    Route::get('/contabilidad/inmueble/{id}', [ContabilidadController::class, 'getClienteInmuebles']);
    Route::post('/contabilidad', [ContabilidadController::class, 'store']);

    //post-venta
    Route::get('/postventa/{id_inmueble}/show', [PostventaController::class, 'show']);
    Route::post('/postventa/{desarrollo}', [PostventaController::class, 'getPostVentas']);
    Route::post('postventa/{id_inmueble}/cita', [PostventaController::class, 'storeCita']);
    Route::post('postventa/{id_inmueble}/ticket', [PostventaController::class, 'storeTicket']);
    Route::post('postventa/{id_inmueble}/updateticket', [PostventaController::class, 'updateTicket']);
    Route::post('postventa/{id_inmueble}/entrega', [PostventaController::class, 'entregaInmueble']);
    Route::post('postventa/{id_inmueble}/cerrarticket', [PostventaController::class, 'cerrarTicket']);
    Route::post('postventa/{id_inmueble}/documentos', [PostventaController::class, 'storeFile']);

    //Telemarketing
    Route::get('/telemarketing/get/{empresa}', [TelemarketingController::class, 'getClientes']);
    Route::post('/telemarketing/update', [TelemarketingController::class, 'update']);
    Route::post('/telemarketing/save/{userId}', [TelemarketingController::class, 'store']);

    //desarrollos
    Route::get('desarrollos/telemarketing/{empresa}', [DesarrollosController::class, 'getOnlyDesarrollo']);
    Route::get('desarrollos/{pageSize}/{currentPage}/{empresa}', [DesarrollosController::class, 'getDesarrollos']);
    Route::get('desarrollos/{id}', [DesarrollosController::class, 'getDesarrollo']);
    Route::post('desarrollos/save', [DesarrollosController::class, 'storeDesarrollo']);
    Route::post('desarrollos/{id}', [DesarrollosController::class, 'update']); //Laravel no soporta FormData en PUT
    Route::delete('desarrollos/delete/{id}', [DesarrollosController::class, 'delete']);

    //usuarios
    Route::get('users/desarrollos/{id}/{empresa}', [UserController::class, 'getUserDesarrollo']);
    Route::get('users/{pageSize}/{currentPage}/{empresa}/{search?}', [UserController::class, 'getUsers']);
    Route::get('sellers/{pageSize}/{currentPage}/{empresa}', [UserController::class, 'getSellers']);
    Route::get('users/{id}/{empresa}', [UserController::class, 'getUser']);
    Route::post('users/new', [UserController::class, 'store']);
    Route::post('users/password/{id}', [UserController::class, 'passwordChange']);
    Route::post('users/suspend/{id}', [UserController::class, 'suspend']);
    Route::put('users/update/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'delete']);

    //Prototipos
    Route::get('prototipo/clone/{desarrollo}/{empresa}', [PrototipoController::class, 'getPrototiposToClone']);
    Route::get('prototipos/{desarrollo}', [PrototipoController::class, 'getDesarrolloPrototipos']);
    Route::get('prototipo/{desarrollo}/{pageSize}/{currentPage}', [PrototipoController::class, 'getAll']);
    Route::get('prototipo/{id}', [PrototipoController::class, 'get']);
    Route::post('prototipo/{sembrado}', [PrototipoController::class, 'store']);
    Route::post('prototipo/clone/{prototipo}/{desarrollo}', [PrototipoController::class, 'clonarPrototipo']);
    Route::post('prototipo/update/{id}', [PrototipoController::class, 'update']); //Laravel no soporta FormData en PUT
    Route::delete('prototipo/{id}', [PrototipoController::class, 'destroy']);

    //Inmueble
    Route::get('inmueble/{desarrollo}/{pageSize}/{currentPage}', [InmuebleController::class, 'getAll']);
    Route::get('inmueble/information/{id}', [InmuebleController::class, 'getInmueble']);
    Route::get('inmueble/{id}', [InmuebleController::class, 'get']);
    Route::post('inmueble', [InmuebleController::class, 'store']);
    Route::put('inmueble/{id}', [InmuebleController::class, 'update']);
    Route::delete('inmueble/{id}', [InmuebleController::class, 'destroy']);
    Route::post('inmueble/apartar', [InmuebleController::class, 'apartar']);
    Route::post('inmueble/cancelA', [InmuebleController::class, 'cancelarApartado']);
    Route::post('inmueble/titular', [InmuebleController::class, 'titular']);
    Route::post('inmueble/cancelT', [InmuebleController::class, 'cancelarTitular']);

    //Documentos
    Route::get('/documento/{hash}', [DocumentosController::class, 'getDocumentsByClient']);

    //sembrado - User
    Route::get('sembrado/{id}', [DesarrollosController::class, 'getSembrado']);
    Route::get('sembrado/points/{id}', [InmuebleController::class, 'getPoints']);
    Route::post('desarrollo/map/{id}', [DesarrollosController::class, 'updateMap']); //Laravel no soporta FormData en PUT

    //Empresa
    Route::get('empresa/{id}', [EmpresaController::class, 'getEmpresa']);

    //Dashboard
    Route::get('/dashboard/get/{empresa}', [DashboardController::class, 'getClientes']);
    Route::post('/dashboard/update', [DashboardController::class, 'update']);
  
});

Route::get('empresas', [EmpresaController::class, 'getAll']);
Route::post('empresas', [EmpresaController::class, 'store']);

/* Chatbot FB */
Route::get('/vendedores/searhseller', [ChatbotController::class, 'searchSeller']);
Route::get('/chat/desarrollos', [ChatbotController::class, 'desarrollos']);
Route::post('/seguimientos', [ChatbotController::class, 'saveSeguimiento']);
Route::post('/prospectos', [ChatbotController::class, 'newProspect']);

Route::get('map/{name}', [ImageController::class, 'getMap']);
Route::get('image/{id}/{name}', [ImageController::class, 'getPrototype']);
Route::get('documents/inmueble/{id}/{name}', [ImageController::class, 'getDocument']);
