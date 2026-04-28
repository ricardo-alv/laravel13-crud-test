<?php

namespace App\Services;

use App\Models\Pessoa;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PessoaService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Pessoa::query()
            ->with('produtos')
            ->latest()
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Pessoa
    {
        return Pessoa::with('produtos')->findOrFail($id);
    }

    public function create(array $data): Pessoa
    {
        return DB::transaction(function () use ($data) {
            $pessoa = Pessoa::create($data);

            if (!empty($data['produtos'])) {
                $this->syncProdutos($pessoa, $data['produtos']);
            }

            return $pessoa->load('produtos');
        });
    }

    public function update(Pessoa $pessoa, array $data): Pessoa
    {
        return DB::transaction(function () use ($pessoa, $data) {
            $pessoa->update($data);

            if (array_key_exists('produtos', $data)) {
                $this->syncProdutos($pessoa, $data['produtos']);
            }

            return $pessoa->load('produtos');
        });
    }

    public function delete(Pessoa $pessoa): bool
    {
        return $pessoa->delete();
    }

    private function syncProdutos(Pessoa $pessoa, array $produtos): void
    {
        $sync = [];
        foreach ($produtos as $item) {
            $sync[$item['produto_id']] = [
                'quantidade'     => $item['quantidade'] ?? 1,
                'preco_unitario' => $item['preco_unitario'] ?? null,
            ];
        }
        $pessoa->produtos()->sync($sync);
    }
}