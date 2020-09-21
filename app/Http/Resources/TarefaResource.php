<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TarefaResource extends JsonResource
{
    public static $wrap = 'tarefa';

    public function toArray($request)
    {
        return [
            'type' => 'tarefa',
            'id' => (string) $this->id,
            'attributes' => $this->resource,
        ];
    }
}
