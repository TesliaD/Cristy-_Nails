<?php

use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\returnValueMap;


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

//LOGIN
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


//DASHBOARD
Route::get('/dashboard',function()
{
    return view('dashboard');
});

//AGENDAR
Route::get('/agendar', function() {
    return view('agendar');
})->name('agendar');

Route::post('/agendar', [App\Http\Controllers\AgendarController::class, 'store'])
    ->name('agendar.store');
