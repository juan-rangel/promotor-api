<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Roteiro;
use App\Tarefa;
use App\RoteirosHasTarefas;
use Illuminate\Http\Request;
use App\Http\Resources\TarefaResource;
use App\Http\Resources\RoteiroHasTarefaResource;
use App\Services\RoteiroTarefaService;
use Illuminate\Support\Facades\DB;
use Exception;

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
        try {
            $inputs = json_decode($request->getContent(), true);
        } catch (\Throwable $th) {
            $inputs = $request->all();
        }

        $return = [
            'success' => true,
            'message' => 'cadastro feito com sucesso',
        ];

        try {
            foreach ($inputs['tarefa_id'] as $key => $value) {
                RoteirosHasTarefas::create([
                    'roteiro_id' => $roteiro->id,
                    'tarefa_id' => $key, // corrigir na view pra vir somente o id 
                    'status' => false,
                    'conteudo' => RoteiroTarefaService::getJsonConteudoPadrao()
                ]);
            }
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }

        return response()->json($return);
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
                    // 'tarefa_id' => $tarefa->id
                ])
                ->when($request->filled("observacao"), function ($query) use ($request) {
                    $query->update(['conteudo->observacao' => $request->observacao]);
                })
                ->when($request->filled("produtosCadastrados"), function ($query) use ($request) {
                    $query->update(['conteudo->produtosCadastrados' => 'testeaaaaaaaaaaa']);
                });
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
