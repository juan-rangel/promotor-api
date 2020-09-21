<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public static $wrap = 'usuario';

    public function toArray($request)
    {
        return [
            'type' => 'usuario',
            'id' => (string) $this->id,
            'attributes' => $this->resource,
        ];
    }

    public function with($request)
    {
        return [
            'included' => [
                'roteiros' => [
                    'data' => RoteiroResource::collection($this->roteiros()->get()),
                    'meta' => [
                        'total' => $this->roteiros()->count(),
                    ],
                ]
            ],
            'links' => [
                'self' => route('usuarios.index'),
            ],
            'meta' => [
                'total' => $this->count(),
            ],
        ];
    }
}
