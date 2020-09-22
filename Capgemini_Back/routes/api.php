<?php

use App\Conta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', 'ContaController@index');

Route::get('/buscarConta', 'ContaController@buscarConta');
Route::get('/saldo', 'ContaController@saldo');

Route::post('/deposito', 'ContaController@deposito');
Route::post('/saque', 'ContaController@saque');

//Route::get('/deposito_terceiro/{agencia}/{agencia_digito}/{conta}/{conta_digito}/{valor}', 'ContaController@deposito_terceiro');