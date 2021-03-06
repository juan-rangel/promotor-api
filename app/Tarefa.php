<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'apresentacao_ordem',
        'icone'
    ];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
}
