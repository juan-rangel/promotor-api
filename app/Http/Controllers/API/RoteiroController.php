<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoteiroResource;
use App\Roteiro;
use App\RoteirosHasTarefas;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoteiroPost;
use Exception;

class RoteiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RoteiroResource::collection(Roteiro::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoteiroPost $request)
    {
        $inputs = json_decode($request->getContent(), true);

        $return = [
            'success' => true,
            'message' => 'cadastro feito com sucesso',
        ];

        $roteiro = new Roteiro([
            'cliente_id' => $inputs['cliente_id'],
            'usuario_id' => $inputs['usuario_id'],
            'data_execucao' => $inputs['data_execucao'],
            'ordem_execucao' => $inputs['ordem_execucao'],
        ]);

        try {
            $roteiro->save();
            $return['model'] = $roteiro;
        } catch (\Throwable $th) {
            throw new Exception('não conseguimos realizar o cadastro do roteiro');
        }

        try {
            foreach ($inputs['tarefa_id'] as $key => $value) {
                RoteirosHasTarefas::create([
                    'roteiro_id' => $roteiro->id,
                    'tarefa_id' => $key, // corrigir na view pra vir somente o id 
                    'status' => false,
                ]);
            }
        } catch (\Throwable $th) {
            throw new Exception('não conseguimos realizar o cadastro das tarefas do roteiro');
        }

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function show(Roteiro $roteiro)
    {
        return new RoteiroResource($roteiro);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRoteiroPost $request, Roteiro $roteiro)
    {
        $inputs = json_decode($request->getContent(), true);

        $return = [
            'success' => true,
            'message' => 'atualização feita com sucesso',
        ];

        try {
            $roteiro->cliente_id = $inputs['cliente_id'];
            $roteiro->data_execucao = $inputs['data_execucao'];
            $roteiro->ordem_execucao = $inputs['ordem_execucao'];
            $roteiro->usuario_id = $inputs['usuario_id'];
            $roteiro->save();

            $return['model'] = $roteiro;
        } catch (\Throwable $th) {
            throw new Exception('não realizar a atualização');
        }

        try {
            RoteirosHasTarefas::where('roteiro_id', $roteiro->id)->delete();
            // RoteirosHasTarefas::where(1)->truncate();
            foreach ($inputs['tarefa_id'] as $key => $value) {
                RoteirosHasTarefas::create([
                    'roteiro_id' => $roteiro->id,
                    'tarefa_id' => $key, // corrigir na view pra vir somente o id 
                    'status' => false,
                ]);
            }
        } catch (\Throwable $th) {
            throw new Exception('não conseguimos realizar o cadastro das tarefas do roteiro');
        }

        return response()->json($return);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Roteiro $roteiro)
    {
        return response()->json($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function destroyMultiples(string $roteiro_ids)
    {
        $idsRoteiro = explode(',', $roteiro_ids);

        $return = [
            'success' => true,
            'message' => 'atualização feita com sucesso',
        ];

        try {
            RoteirosHasTarefas::whereIn('roteiro_id', $idsRoteiro)->delete();
            Roteiro::whereIn('id', $idsRoteiro)->delete();
        } catch (\Throwable $th) {
            throw new Exception('não conseguimos remover estes roteiros');
        }

        return response()->json($return);
    }
}
