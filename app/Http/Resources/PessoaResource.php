<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PessoaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id'              => $this->id,
            'nome'            => $this->nome,
            'email'           => $this->email,
            'cpf'             => $this->cpf,
            'telefone'        => $this->telefone,
            'data_nascimento' => $this->data_nascimento?->format('Y-m-d'),
            'produtos'        => ProdutoResource::collection($this->whenLoaded('produtos')),
            'created_at'      => $this->created_at?->toISOString(),
            'updated_at'      => $this->updated_at?->toISOString(),
        ];
    }
}
