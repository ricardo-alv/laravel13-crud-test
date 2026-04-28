<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'      => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco'     => ['required', 'numeric', 'min:0'],
            'estoque'   => ['nullable', 'integer', 'min:0'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'ativo'     => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'  => 'O nome do produto é obrigatório.',
            'preco.required' => 'O preço é obrigatório.',
            'preco.min'      => 'O preço não pode ser negativo.',
        ];
    }
}
