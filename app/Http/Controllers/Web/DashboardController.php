<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Empresa,
    Inmueble,
    User,
};

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'menu' => 'dashboard',
            'companies' => Empresa::all(),
            'properties' => Inmueble::all(),
            'users' => User::all(),

        ];
        return view('dashboard', $data);
    }
}
