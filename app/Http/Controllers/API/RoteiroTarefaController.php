<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Roteiro;
use App\Tarefa;
use Illuminate\Http\Request;
use App\Http\Resources\TarefaResource;

class RoteiroTarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function index(Roteiro $roteiro)
    {
        return TarefaResource::collection($roteiro->tarefas()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Roteiro $roteiro)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Roteiro  $roteiro
     * @param  \App\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Roteiro $roteiro, Tarefa $tarefa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Roteiro  $roteiro
     * @param  \App\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Roteiro $roteiro, Tarefa $tarefa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Roteiro  $roteiro
     * @param  \App\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roteiro $roteiro, Tarefa $tarefa)
    {
        //
    }
}
