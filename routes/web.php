<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware(['cors'])->group(function () {
    Route::apiResources([
        'usuarios' => 'API\UsuarioController',
        'usuarios.roteiros' => 'API\UsuarioRoteiroController',
        'roteiros.tarefas' => 'API\RoteiroTarefaController',
        'roteiros' => 'API\RoteiroController',
        'clientes' => 'API\ClienteController',
        'tarefas' => 'API\TarefaController',
    ]);
});
