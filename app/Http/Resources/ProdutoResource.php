<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'nome'      => $this->nome,
            'descricao' => $this->descricao,
            'preco'     => (float) $this->preco,
            'estoque'   => $this->estoque,
            'categoria' => $this->categoria,
            'ativo'     => $this->ativo,
            'pivot'     => $this->when($this->pivot, [
                'quantidade'     => $this->pivot?->quantidade,
                'preco_unitario' => $this->pivot?->preco_unitario ? (float) $this->pivot->preco_unitario : null,
            ]),
            'pessoas'    => PessoaResource::collection($this->whenLoaded('pessoas')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
