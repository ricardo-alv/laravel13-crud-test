<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Pagination\LengthAwarePaginator;

class ProdutoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Produto::query()
            ->with('pessoas')
            ->latest()
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Produto
    {
        return Produto::with('pessoas')->findOrFail($id);
    }

    public function create(array $data): Produto
    {
        return Produto::create($data);
    }

    public function update(Produto $produto, array $data): Produto
    {
        $produto->update($data);

        return $produto->fresh('pessoas');
    }

    public function delete(Produto $produto): bool
    {
        return $produto->delete();
    }
}