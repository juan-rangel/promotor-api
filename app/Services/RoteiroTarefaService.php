<?php

namespace App\Services;

class RoteiroTarefaService
{
    public static function getJsonConteudoPadrao()
    {
        return (string) json_encode([
            'observacao' => null
        ]);
    }
}
