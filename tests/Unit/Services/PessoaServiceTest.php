<?php

use App\Models\Pessoa;
use App\Models\Produto;
use App\Services\PessoaService;

describe('PessoaService', function () {
    beforeEach(function () {
        $this->service = app(PessoaService::class);
    });

    it('cria pessoa e retorna com produtos carregados', function () {
        $produto = Produto::factory()->create();

        $pessoa = $this->service->create([
            'nome'  => 'Teste Service',
            'email' => 'service@test.com',
            'cpf'   => '000.000.000-00',
            'produtos' => [
                ['produto_id' => $produto->id, 'quantidade' => 5],
            ],
        ]);

        expect($pessoa)->toBeInstanceOf(Pessoa::class)
            ->and($pessoa->produtos)->toHaveCount(1)
            ->and($pessoa->produtos->first()->pivot->quantidade)->toBe(5);
    });

    it('atualiza pessoa e sincroniza produtos', function () {
        $pessoa   = Pessoa::factory()->create();
        $produto1 = Produto::factory()->create();
        $produto2 = Produto::factory()->create();

        $pessoa->produtos()->attach($produto1->id, ['quantidade' => 1]);

        $this->service->update($pessoa, [
            'nome'  => $pessoa->nome,
            'email' => $pessoa->email,
            'cpf'   => $pessoa->cpf,
            'produtos' => [
                ['produto_id' => $produto2->id, 'quantidade' => 2],
            ],
        ]);

        expect($pessoa->fresh('produtos')->produtos)->toHaveCount(1)
            ->and($pessoa->fresh('produtos')->produtos->first()->id)->toBe($produto2->id);
    });

    it('faz soft delete da pessoa', function () {
        $pessoa = Pessoa::factory()->create();

        $this->service->delete($pessoa);

        $this->assertSoftDeleted('pessoas', ['id' => $pessoa->id]);
    });
});