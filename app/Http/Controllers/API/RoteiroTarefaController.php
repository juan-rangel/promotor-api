<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Roteiro;
use App\Tarefa;
use App\RoteirosHasTarefas;
use Illuminate\Http\Request;
use App\Http\Resources\TarefaResource;
use App\Http\Resources\RoteiroHasTarefaResource;
use Illuminate\Support\Facades\DB;

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
        return new RoteiroHasTarefaResource(RoteirosHasTarefas::where([
            'roteiro_id' => $roteiro->id,
            'tarefa_id' => $tarefa->id
        ])->first());
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
        $return = [
            'success' => true,
            'message' => 'atualização feita com sucesso',
        ];

        try {
            DB::table('roteiros_has_tarefas')
                ->where([
                    'roteiro_id' => $roteiro->id,
                    'tarefa_id' => $tarefa->id
                ])
                ->update(['conteudo->observacao' => $request->observacao]);
        } catch (\Throwable $th) {
            throw new Exception('não conseguimos realizar a atualização');
        }

        return response()->json($return);
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
