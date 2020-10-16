<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações dos WebServices que compõem as informações no aplicativo
    |--------------------------------------------------------------------------
    |
    | Aqui estão especificadas as configurações necessárias para o consumo dos
    | webservices necessários para compor nossa aplicação.
    |
     */

    // Configurações dos WebServices do Sales Hunter
    'saleshunter' => [
        // Ambiente de Produção
        'production' => [
            'urlBase' => 'http://piloto-sh.abrascort.com.br/api',
        ],

        // Ambiente de Desenvolvimento
        'development' => [
            'urlBase' => 'http://127.0.0.1:8000/api',
        ],

        // Rotas dos WebServices
        'rotas' => [
            'cliente_ultimos_produtos_comprados' => '/clientes/produtos-comprados/{[{sap_cod_cliente}]}/produtos',
            'cliente_ultimos_produtos_comprados_produto' => '/clientes/produtos-comprados/{[{sap_cod_cliente}]}/produtos/{[{sap_cod_produto}]}',
            'cliente_produtos_cadastrados' => '/clientes/produtos-cadastrados/{[{sap_cod_cliente}]}',
        ],
    ],

    // Configurações dos WebServices da Simplus
    'simplus' => [
        // Ambiente de Produção
        'production' => [
            'urlBase' => 'https://prod.simplustec.com.br/api/v3',
            'autenticacao' => [
                'bearer' => '3f998e713a6e02287c374fd26835d87e',
            ],

        ],

        // Ambiente de Desenvolvimento
        'development' => [
            'urlBase' => 'https://prod.simplustec.com.br/api/v3',
            'autenticacao' => [
                'bearer' => '3f998e713a6e02287c374fd26835d87e',
            ],
        ],

        // Rotas dos WebServices
        'rotas' => [
            'produto' => '/manufacturer/products',
            'teste' => '/layout',
        ],
    ],
];
