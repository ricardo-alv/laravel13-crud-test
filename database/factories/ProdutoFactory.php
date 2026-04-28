<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        return [
            'nome'      => fake()->words(3, true),
            'descricao' => fake()->sentence(),
            'preco'     => fake()->randomFloat(2, 1, 999),
            'estoque'   => fake()->numberBetween(0, 500),
            'categoria' => fake()->randomElement(['Eletrônicos', 'Roupas', 'Alimentos', 'Livros', 'Casa']),
            'ativo'     => true,
        ];
    }

    public function inativo(): static
    {
        return $this->state(['ativo' => false]);
    }
}