<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Usuario extends Model
{
    use Notifiable;

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
