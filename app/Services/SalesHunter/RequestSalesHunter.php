<?php

namespace App\Services\SalesHunter;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

/**
 * Esta classe faz a consulta ao WebService do Sales Hunter
 * Retorna um array de informações
 */

class RequestSalesHunter
{

    /**
     * Conexão e consulta via curl nos WebServices do Sales Hunter
     * Esse método faz conexão via shell_exec
     *
     * @param string $rota
     * @param array $parametros
     * @return object
     */
    static public function enviarRequest($rota, $parametros = array(), $query = array(), $dados = '', $metodo = 'GET')
    {

        $configuracao = Config::get('api.saleshunter');
        $ambiente = App::environment();
        $pathURL = $configuracao[$ambiente]['urlBase'] . $configuracao['rotas'][$rota];
        $queryURI = (!empty($query) ? '?' . implode('&', $query) : '');

        foreach ($parametros as $chave => $parametro) {
            $pathURL = str_replace("{[{{$chave}}]}", $parametro, $pathURL);
        }

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json'
        ])->$metodo($pathURL . $queryURI, $dados);

        return $response->json();
    }
}
