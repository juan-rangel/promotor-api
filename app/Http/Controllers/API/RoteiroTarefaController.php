<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Roteiro;
use App\Tarefa;
use App\RoteirosHasTarefas;
use Illuminate\Http\Request;
use App\Http\Resources\TarefaResource;
use App\Http\Resources\RoteiroHasTarefaResource;
use App\Notifications\LojaComRuptura;
use App\Services\RoteiroTarefaService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
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
            RoteirosHasTarefas::where([
                'roteiro_id' => $roteiro->id,
                // 'tarefa_id' => $tarefa->id
            ])
                ->when($request->filled("observacao"), function ($query) use ($request) {
                    $query->update(['conteudo->observacao' => $request->observacao]);
                })
                ->when($request->filled("conquistaRealizada"), function ($query) use ($request, $roteiro) {
                    $roteirosHasTarefas = RoteirosHasTarefas::selectRaw('JSON_EXTRACT(conteudo, "$.conquistasRealizadas") as conquistasRealizadas')
                        ->where([
                            'roteiro_id' => $roteiro->id,
                            // 'tarefa_id' => $tarefa->id
                        ])->first();

                    $documento = collect(json_decode($roteirosHasTarefas->conquistasRealizadas));

                    //Exemplo: gKu3ZZ9R 2020-11-04 022114
                    $fileAntes = '/antes-depois/' . $request->conquistaRealizada['categoria'] . '/antes-' . Str::random(8) . Carbon::now()->format(' Y-m-d His') . '.png';
                    $fileDepois = '/antes-depois/' . $request->conquistaRealizada['categoria'] . '/depois-' . Str::random(8) . Carbon::now()->format(' Y-m-d His') . '.png';

                    $conquista = [
                        'categoria' => $request->conquistaRealizada['categoria'],
                        'antes' => [
                            'criado_em' => Carbon::now(),
                            'path' => asset('storage' . $fileAntes)
                        ],
                        'depois' => [
                            'criado_em' => Carbon::now(),
                            'path' => asset('storage' . $fileDepois)
                        ]
                    ];

                    $documento->push($conquista);
                    Storage::disk('public')->put($fileAntes, base64_decode($request->conquistaRealizada['antes']));
                    Storage::disk('public')->put($fileDepois, base64_decode($request->conquistaRealizada['depois']));

                    $query->update(['conteudo->conquistasRealizadas' => json_decode($documento)]);
                })
                ->when($request->filled("produtosCadastrados"), function ($query) use ($request, $roteiro) {
                    $roteirosHasTarefas = RoteirosHasTarefas::selectRaw('JSON_EXTRACT(conteudo, "$.produtosCadastrados") as produtosCadastrados')
                        ->where([
                            'roteiro_id' => $roteiro->id,
                            // 'tarefa_id' => $tarefa->id
                        ])->first();

                    $documento = collect(json_decode($roteirosHasTarefas->produtosCadastrados));
                    $documento->firstWhere('sap_cod_produto', $request->produtosCadastrados['sap_cod_produto'])->estoque_fisico = $request->produtosCadastrados['estoque'];

                    $query->update(['conteudo->produtosCadastrados' => json_decode($documento)]);
                })
                ->when($request->filled("produtosVencimentos"), function ($query) use ($request, $roteiro) {
                    $roteirosHasTarefas = RoteirosHasTarefas::selectRaw('JSON_EXTRACT(conteudo, "$.produtosCadastrados") as produtosCadastrados')
                        ->where([
                            'roteiro_id' => $roteiro->id,
                            // 'tarefa_id' => $tarefa->id
                        ])->first();

                    $documento = collect(json_decode($roteirosHasTarefas->produtosCadastrados));
                    $documento->firstWhere('sap_cod_produto', $request->produtosVencimentos['sap_cod_produto'])->vencimentos = $request->produtosVencimentos['vencimentos'];

                    $query->update(['conteudo->produtosCadastrados' => json_decode($documento)]);
                })
                ->when($request->filled("produtosConcorrentes"), function ($query) use ($request, $roteiro) {
                    $roteirosHasTarefas = RoteirosHasTarefas::selectRaw('JSON_EXTRACT(conteudo, "$.produtosCadastrados") as produtosCadastrados')
                        ->where([
                            'roteiro_id' => $roteiro->id,
                            // 'tarefa_id' => $tarefa->id
                        ])->first();

                    $documento = collect(json_decode($roteirosHasTarefas->produtosCadastrados));
                    $documento->firstWhere('sap_cod_produto', $request->produtosConcorrentes['sap_cod_produto'])->concorrentes = $request->produtosConcorrentes['concorrentes'];

                    $query->update(['conteudo->produtosCadastrados' => json_decode($documento)]);
                });
        } catch (\Throwable $th) {
            return response([$th->getMessage()]);
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

    /**
     * Send mail to comunicate above rupture.
     *
     * @param  \App\Roteiro  $roteiro
     * @param  \App\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function comunicateRupture(Roteiro $roteiro, Tarefa $tarefa)
    {
        $return = [
            'success' => true,
            'message' => 'cadastro feito com sucesso',
        ];

        try {
            $roteiroHasTarefas = RoteirosHasTarefas::where([
                'roteiro_id' => $roteiro->id,
                'tarefa_id' => $tarefa->id, // corrigir na view pra vir somente o id 
            ])->first();

            $produtosCadastrados = collect(json_decode($roteiroHasTarefas->conteudo)->produtosCadastrados);

            $produtosComRuptura = $produtosCadastrados->filter(function ($prduto) {
                return ($prduto->estoque_fisico >= 0 && $prduto->estoque_fisico <= 10);
            });

            $detalhes = collect([
                'produtos' => $produtosComRuptura,
                'cliente' => $roteiro->cliente,
                'usuario' => $roteiro->usuario,
                'roteiro' => $roteiro
            ]);

            $roteiro->usuario->notify(new LojaComRuptura($detalhes));
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }

        return response()->json($return);
    }
}
