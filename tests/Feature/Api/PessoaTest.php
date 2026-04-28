<?php

use App\Models\Pessoa;
use App\Models\Produto;
use Termwind\Components\Dd;

// ──────────────────────────────────────────────
// INDEX
// ──────────────────────────────────────────────


describe('GET /api/v1/pessoas', function () {
    it('retorna lista paginada de pessoas', function () {
        Pessoa::factory()->count(3)->create();

        $response = $this->getJson(route('pessoas.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nome', 'email', 'cpf'],
                ],
                'meta' => ['current_page', 'total'],
            ]);

        expect($response->json('meta.total'))->toBe(3);
    });

    it('retorna lista vazia quando não há pessoas', function () {
        $response = $this->getJson(route('pessoas.index'));

        $response->assertOk();
        expect($response->json('meta.total'))->toBe(0);
    });
});

// ──────────────────────────────────────────────
// STORE
// ──────────────────────────────────────────────
describe('POST /api/v1/pessoas', function () {
    it('cria uma nova pessoa com dados válidos', function () {
        $payload = [
            'nome'            => 'João Silva',
            'email'           => 'joao@example.com',
            'cpf'             => '123.456.789-09',
            'telefone'        => '(81) 99999-0000',
            'data_nascimento' => '1990-05-15',
        ];

        $response = $this->postJson(route('pessoas.store'), $payload);

        $response->assertCreated()
            ->assertJsonPath('data.nome', 'João Silva')
            ->assertJsonPath('data.email', 'joao@example.com');

        $this->assertDatabaseHas('pessoas', ['email' => 'joao@example.com']);
    });

    it('cria pessoa com produtos associados', function () {
        $produto = Produto::factory()->create();

        $payload = [
            'nome'  => 'Maria Costa',
            'email' => 'maria@example.com',
            'cpf'   => '987.654.321-00',
            'produtos' => [
                ['produto_id' => $produto->id, 'quantidade' => 2, 'preco_unitario' => 49.90],
            ],
        ];

        $response = $this->postJson(route('pessoas.store'), $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('pessoa_produto', [
            'pessoa_id'  => $response->json('data.id'),
            'produto_id' => $produto->id,
            'quantidade' => 2,
        ]);
    });

    it('falha ao criar pessoa com e-mail duplicado', function () {
        Pessoa::factory()->create(['email' => 'duplicado@example.com']);

        $response = $this->postJson(route('pessoas.store'), [
            'nome'  => 'Outro Nome',
            'email' => 'duplicado@example.com',
            'cpf'   => '111.222.333-44',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    });

    it('falha ao criar pessoa sem campos obrigatórios', function () {
        $response = $this->postJson(route('pessoas.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['nome', 'email', 'cpf']);
    });
});

// ──────────────────────────────────────────────
// SHOW
// ──────────────────────────────────────────────
describe('GET /api/v1/pessoas/{id}', function () {
    it('retorna pessoa com seus produtos', function () {
        $pessoa = Pessoa::factory()->create();
        $produto = Produto::factory()->create();
        $pessoa->produtos()->attach($produto->id, ['quantidade' => 1]);

        $response = $this->getJson(route('pessoas.show', $pessoa->id));

        $response->assertOk()
            ->assertJsonPath('data.id', $pessoa->id)
            ->assertJsonStructure(['data' => ['id', 'nome', 'email', 'produtos']]);

        expect($response->json('data.produtos'))->toHaveCount(1);
    });

    it('retorna 404 para pessoa inexistente', function () {
        $response = $this->getJson(route('pessoas.show',9999));

        $response->assertNotFound();
    });
});

// ──────────────────────────────────────────────
// UPDATE
// ──────────────────────────────────────────────
describe('PUT /api/v1/pessoas/{id}', function () {
    it('atualiza dados da pessoa', function () {
        $pessoa = Pessoa::factory()->create();

        $response = $this->putJson(route('pessoas.update', $pessoa->id), [
            'nome'  => 'Nome Atualizado',
            'email' => 'atualizado@example.com',
            'cpf'   => $pessoa->cpf,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.nome', 'Nome Atualizado');

        $this->assertDatabaseHas('pessoas', ['id' => $pessoa->id, 'nome' => 'Nome Atualizado']);
    });

    it('atualiza produtos vinculados à pessoa', function () {
        $pessoa   = Pessoa::factory()->create();
        $produto1 = Produto::factory()->create();
        $produto2 = Produto::factory()->create();

        $pessoa->produtos()->attach($produto1->id, ['quantidade' => 1]);

        $this->putJson(route('pessoas.update', $pessoa->id), [
            'nome'  => $pessoa->nome,
            'email' => $pessoa->email,
            'cpf'   => $pessoa->cpf,
            'produtos' => [
                ['produto_id' => $produto2->id, 'quantidade' => 3],
            ],
        ])->assertOk();

        $this->assertDatabaseMissing('pessoa_produto', ['produto_id' => $produto1->id]);
        $this->assertDatabaseHas('pessoa_produto', ['produto_id' => $produto2->id, 'quantidade' => 3]);
    });
});

// ──────────────────────────────────────────────
// DELETE
// ──────────────────────────────────────────────
describe('DELETE /api/v1/pessoas/{id}', function () {
    it('remove pessoa com soft delete', function () {
        $pessoa = Pessoa::factory()->create();

        $response = $this->deleteJson(route('pessoas.destroy', $pessoa->id));

        $response->assertOk()->assertJsonPath('message', 'Pessoa removida com sucesso.');
        $this->assertSoftDeleted('pessoas', ['id' => $pessoa->id]);
    });

    it('retorna 404 ao tentar remover pessoa inexistente', function () {
        $response = $this->deleteJson(route('pessoas.destroy', 9999));

        $response->assertNotFound();
    });
});