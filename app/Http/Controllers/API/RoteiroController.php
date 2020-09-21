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
    public function update(Request $request, Roteiro $roteiro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roteiro $roteiro)
    {
        //
    }
}
