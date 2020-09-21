<?php

namespace App\Http\Controllers\API;

use App\Tarefa;
use App\Http\Controllers\Controller;
use App\Http\Resources\TarefaResource;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TarefaResource::collection(Tarefa::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tarefa  $Tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $Tarefa)
    {
        return new TarefaResource($Tarefa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tarefa  $Tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $Tarefa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tarefa  $Tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $Tarefa)
    {
        //
    }
}
