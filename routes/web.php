<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    DashboardController,
    CompanyController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** Company routes */
    Route::resource('companies', CompanyController::class);
});


require __DIR__.'/auth.php';
