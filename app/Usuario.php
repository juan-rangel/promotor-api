<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $hidden = [
        'senha',
    ];

    public function roteiros()
    {
        return $this->hasMany(Roteiro::class, 'usuario_id');
    }
}
