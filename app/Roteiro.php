<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roteiro extends Model
{
    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'data_execucao',
        'ordem_execucao'
    ];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function tarefas()
    {
        return $this->belongsToMany(Tarefa::class, 'roteiros_has_tarefas');
    }
}
