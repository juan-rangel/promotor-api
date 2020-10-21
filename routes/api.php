<?php

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

Route::apiResources([
    'usuarios' => 'API\UsuarioController',
    'usuarios.roteiros' => 'API\UsuarioRoteiroController',
    'roteiros.tarefas' => 'API\RoteiroTarefaController',
    'roteiros' => 'API\RoteiroController',
    'clientes' => 'API\ClienteController',
    'tarefas' => 'API\TarefaController',
]);

Route::delete('roteiros/{roteiros}/multiples', 'API\RoteiroController@destroyMultiples')->name('roteiros.destroy-multiples');;
Route::post('usuarios/auth', 'API\UsuarioController@auth')->name('usuarios.auth');;
Route::get('roteiros/{roteiro}/tarefas/{tarefa}/comunicar-ruptura', 'API\RoteiroTarefaController@comunicateRupture')->name('roteiros.tarefas.comunicar-rupture');;
