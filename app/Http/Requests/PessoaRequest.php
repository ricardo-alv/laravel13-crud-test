<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PessoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pessoaId = $this->route('pessoa')?->id;

        return [
            'nome'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', Rule::unique('pessoas', 'email')->ignore($pessoaId)->whereNull('deleted_at')],
            'cpf'            => ['required', 'string', 'max:14', Rule::unique('pessoas', 'cpf')->ignore($pessoaId)->whereNull('deleted_at')],
            'telefone'       => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['nullable', 'date'],
            'produtos'       => ['nullable', 'array'],
            'produtos.*.produto_id'     => ['required_with:produtos', 'integer', 'exists:produtos,id'],
            'produtos.*.quantidade'     => ['nullable', 'integer', 'min:1'],
            'produtos.*.preco_unitario' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'           => 'O nome é obrigatório.',
            'email.required'          => 'O e-mail é obrigatório.',
            'email.unique'            => 'Este e-mail já está em uso.',
            'cpf.required'            => 'O CPF é obrigatório.',
            'cpf.unique'              => 'Este CPF já está cadastrado.',
            'produtos.*.produto_id.exists' => 'Produto inválido.',
        ];
    }
}
