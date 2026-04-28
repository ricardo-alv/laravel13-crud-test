<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PessoaFactory extends Factory
{
    protected $model = Pessoa::class;

    public function definition(): array
    {
        return [
            'nome'            => fake()->name(),
            'email'           => fake()->unique()->safeEmail(),
            'cpf'             => $this->fakeCpf(),
            'telefone'        => fake()->phoneNumber(),
            'data_nascimento' => fake()->date('Y-m-d', '-18 years'),
        ];
    }

    private function fakeCpf(): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[] = rand(0, 9);
        }

        $d1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $d1 += $n[$i] * (10 - $i);
        }
        $d1 = ($d1 % 11 < 2) ? 0 : 11 - ($d1 % 11);

        $d2 = 0;
        for ($i = 0; $i < 9; $i++) {
            $d2 += $n[$i] * (11 - $i);
        }
        $d2 += $d1 * 2;
        $d2 = ($d2 % 11 < 2) ? 0 : 11 - ($d2 % 11);

        $cpf = implode('', $n) . $d1 . $d2;

        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
}