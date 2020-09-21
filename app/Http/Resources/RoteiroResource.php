<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoteiroResource extends JsonResource
{
    public static $wrap = 'roteiro';

    public function toArray($request)
    {
        $this->resolveQuery($request);

        return [
            'type' => 'roteiro',
            'id' => (string) $this->id,
            'attributes' => $this->resource,
            'relationships' => [
                'usuario' => [
                    'data' => [
                        'id' => (string) $this->usuario_id,
                    ],
                    'links' => [
                        'self' => route('usuarios.show', ['usuario' => $this->usuario_id]),
                        'related' => route('usuarios.roteiros.index', ['usuario' => $this->usuario_id]),
                    ]
                ],
                'cliente' => [
                    'data' => [
                        'id' => (string) $this->cliente_id,
                    ],
                    'links' => [
                        'self' => route('clientes.show', ['cliente' => $this->cliente_id]),
                    ]
                ],
                'tarefas' => [
                    'data' => [
                        'id' => (string) $this->id,
                    ],
                    'links' => [
                        // 'self' => route('usuarios.show', ['usuario' => $this->usuario_id]),
                        'related' => route('roteiros.tarefas.index', ['roteiro' => $this->id]),
                    ]
                ],
            ],
        ];
    }

    public function with($request)
    {
        return [
            'included' => [
                new UsuarioResource($this->usuario()->first()),
                new ClienteResource($this->cliente()->first()),
            ],
            'links' => [
                'self' => route('roteiros.index'),
            ],
            'meta' => [
                'total' => $this->count(),
            ],
        ];
    }

    private function resolveQuery($request)
    {
        if ($request->filled('related')) {
            $this->load(explode(',', $request->get('related')));
        }
    }
}
