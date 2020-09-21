<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public static $wrap = 'cliente';

    public function toArray($request)
    {
        return [
            'type' => 'cliente',
            'id' => (string) $this->id,
            'attributes' => $this->resource,
        ];
    }
}
