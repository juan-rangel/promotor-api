<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoteirosHasTarefas extends Model
{
    protected $fillable = [
        'roteiro_id',
        'tarefa_id',
        'status',
        'conteudo',
    ];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
}
