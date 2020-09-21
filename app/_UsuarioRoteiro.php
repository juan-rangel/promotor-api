<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioRoteiro extends Model
{
    protected $table = 'usuarios_has_roteiros';

    protected $hidden = [
        'senha',
    ];

    public function roteiro()
    {
        return $this->belongsTo(Roteiro::class, 'roteiro_id');
    }
}
