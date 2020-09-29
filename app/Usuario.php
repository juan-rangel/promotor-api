<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $hidden = [
        'senha',
    ];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    public function roteiros()
    {
        return $this->hasMany(Roteiro::class, 'usuario_id');
    }
}
