<?php

namespace App\Jobs;

use MultipleIterator;
use App\Cliente;
use App\RoteirosHasTarefas;
use App\Services\SalesHunter\RequestSalesHunter;
use App\Services\Simplus\RequestSimplus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

/**
 * Armazena os produtos cadastrados de cada cliente no roteiro
 * 
 * @param Cliente $cliente
 * @return void
 */
class JobStoreProdutoCadastrado implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cliente;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
        /**
         * Usar a linha comentada a baixo caso queira rodar o job sem precisar de queue
         */
        // $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $roteiros = $this->cliente->roteiros()->where('status', 0)->get();

        foreach ($roteiros as &$roteiro) {
            $produtosCadastrados = RequestSalesHunter::enviarRequest('cliente_produtos_cadastrados', ['sap_cod_cliente' => $this->cliente->sap_cod_cliente]);
            $this->injectJson($produtosCadastrados);
            RoteirosHasTarefas::where('roteiro_id', $roteiro->id)->update(['conteudo->produtosCadastrados' => $produtosCadastrados]);
            unset($roteiro);
            unset($produtosCadastrados);
        }

        unset($roteiros);
    }

    /**
     * Método para buscar as imagens na simplus e os ultimos produtos que foram comprados pela empresa
     * 
     * @todo incluir o ws de eans para buscar o ean correto do produto
     * necessário para encontrar a imagem exata do produto na simplus
     */
    private function injectJson(&$produtosCadastrados)
    {
        $getGenerator = fn ($req) => yield $req;

        foreach ($produtosCadastrados as $k => &$produto) {
            try {
                $produto['estoque_fisico'] = -1;
                $produto['vencimentos'] = [];
                $produto['concorrentes'] = [];
                $saleshunter = $getGenerator(RequestSalesHunter::enviarRequest('cliente_ultimos_produtos_comprados_produto', [
                    'sap_cod_cliente' => $this->cliente->sap_cod_cliente,
                    'sap_cod_produto' => $produto['sap_cod_produto']
                ]));

                $ean = isset($produto['ean13']) ? $produto['ean13'] : ($k % 2 == 0 ? '7896063103016' : '7896863461613');
                $simplus = $getGenerator(RequestSimplus::enviarRequest('produto', array(), array(), ['gtin' => $ean], 'post'));

                $multipleIterator = new MultipleIterator();
                $multipleIterator->attachIterator($saleshunter);
                $multipleIterator->attachIterator($simplus);

                foreach ($multipleIterator as list($saleshunter, $simplus)) {
                    self::injectPayloadSimplus($simplus);
                    $produto['notas'] = $saleshunter;
                    $produto['info_amigavel'] = $simplus;
                }

                unset($ean);
                unset($multipleIterator);
                unset($simplus);
                unset($saleshunter);
            } catch (\Throwable $th) {
                print_r($th->getMessage());
                //throw $th;
            }
        }
        unset($getGenerator);
    }

    private static function injectPayloadSimplus(&$response)
    {
        $pivot = [
            'descricao' => '',
            'categoria' => 'Não listado',
            'imagem' => [
                'url' => 'http://saleshunter.vitao.com.br/assets/images/anexo-produto-padrao.png'
            ]
        ];

        try {
            $pivot['descricao'] = $response['produtos'][0]['descricao'];
            $pivot['categoria'] = $response['produtos'][0]['categoriaProduto']['nome'];
        } catch (\Throwable $th) {
        }

        try {
            /**
             * 
             * Busca as imagens que são PNG e que estão na posição frontal
             */
            $pivot['imagem'] = collect(Arr::where($response['produtos'][0]['ativos'], function ($value) {
                return Str::containsAll($value['url'], ['_1_', '.jpg']);
            }));

            /** 
             * 
             * Busca o tamanho de cada imagem hospedada na simplus
             */

            $pivotCollectionImagens = $pivot['imagem']->map(function ($item) {
                $item['size'] = (float) Http::get($item['url'])->header('Content-Length');
                return $item;
            });

            /**
             * 
             * Pega a imagem com a menor quantidade de mb
             */
            unset($pivot['imagem']);
            $pivot['imagem'] = $pivotCollectionImagens->firstWhere('size', $pivotCollectionImagens->min('size'));
            unset($pivotCollectionImagens);
        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }

        $response = $pivot;
        unset($pivot);
        return $response;
    }
}
