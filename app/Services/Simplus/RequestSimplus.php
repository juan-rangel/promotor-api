<?php

namespace App\Services\Simplus;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

/**
 * Esta classe faz a consulta ao WebService da Simplus
 * Retorna um array de informações
 */

class RequestSimplus
{

    /**
     * Conexão e consulta via curl nos WebServices da Simplus
     * Esse método faz conexão via shell_exec
     *
     * @param string $rota
     * @param array $parametros
     * @return object
     */
    static public function enviarRequest($rota, $parametros = array(), $query = array(), $dados = array(), $metodo = 'GET')
    {
        $configuracao = Config::get('api.simplus');
        $ambiente = App::environment();
        $token = $configuracao[$ambiente]['autenticacao']['bearer'];
        $pathURL = $configuracao[$ambiente]['urlBase'] . $configuracao['rotas'][$rota];
        $queryURI = (!empty($query) ? '?' . implode('&', $query) : '');

        foreach ($parametros as $chave => $parametro) {
            $pathURL = str_replace("{[{{$chave}}]}", $parametro, $pathURL);
        }

        $response = Http::withHeaders([
            'authorization' => $token,
            'accept' => 'application/json',
            'content-type' => 'application/json'
        ])->$metodo($pathURL . $queryURI, $dados);

        return $response->json();
    }
}
