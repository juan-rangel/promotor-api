<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoteiroHasTarefaResource extends JsonResource
{
    public static $wrap = 'roteiro_has_tarefa';

    public function toArray($request)
    {
        return [
            'type' => 'roteiro_has_tarefa',
            'id' => (string) $this->id,
            'attributes' => $this->resource,
        ];
    }
}
