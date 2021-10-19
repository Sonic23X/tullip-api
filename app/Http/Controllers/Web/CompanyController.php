<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Empresa,
    User,
};

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'menu' => 'company',
            'companies' => Empresa::all()
        ];
        return view('pages.company.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.company.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        return redirect('/adaccess/companies');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
