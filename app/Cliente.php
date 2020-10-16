<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function roteiros()
    {
        return $this->hasMany(Roteiro::class);
    }
}
