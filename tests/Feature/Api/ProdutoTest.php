<?php

use App\Models\Pessoa;
use App\Models\Produto;
// ──────────────────────────────────────────────
// INDEX
// ──────────────────────────────────────────────
describe('GET /api/v1/produtos', function () {
    it('retorna lista paginada de produtos', function () {
        Produto::factory()->count(5)->create();

        $response = $this->getJson(route('produtos.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nome', 'preco', 'estoque', 'ativo'],
                ],
                'meta' => ['current_page', 'total'],
            ]);

        expect($response->json('meta.total'))->toBe(5);
    });
});

// ──────────────────────────────────────────────
// STORE
// ──────────────────────────────────────────────
describe('POST /api/v1/produtos', function () {
    it('cria um novo produto com dados válidos', function () {
        $payload = [
            'nome'      => 'Notebook Pro',
            'descricao' => 'Notebook de alta performance',
            'preco'     => 4999.90,
            'estoque'   => 10,
            'categoria' => 'Eletrônicos',
            'ativo'     => true,
        ];

        $response = $this->postJson(route('produtos.store'), $payload);

        $response->assertCreated()
            ->assertJsonPath('data.nome', 'Notebook Pro')
            ->assertJsonPath('data.preco', 4999.90);

        $this->assertDatabaseHas('produtos', ['nome' => 'Notebook Pro']);
    });

    it('falha ao criar produto sem nome e preço', function () {
        $response = $this->postJson(route('produtos.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['nome', 'preco']);
    });

    it('falha ao criar produto com preço negativo', function () {
        $response = $this->postJson(route('produtos.store'), [
            'nome'  => 'Produto Inválido',
            'preco' => -10,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['preco']);
    });
});

// ──────────────────────────────────────────────
// SHOW
// ──────────────────────────────────────────────
describe('GET /api/v1/produtos/{id}', function () {
    it('retorna produto com suas pessoas', function () {
        $produto = Produto::factory()->create();
        $pessoa  = Pessoa::factory()->create();
        $produto->pessoas()->attach($pessoa->id, ['quantidade' => 1]);

        $response = $this->getJson(route('produtos.show',$produto->id));

        $response->assertOk()
            ->assertJsonPath('data.id', $produto->id)
            ->assertJsonStructure(['data' => ['id', 'nome', 'preco', 'pessoas']]);

        expect($response->json('data.pessoas'))->toHaveCount(1);
    });

    it('retorna 404 para produto inexistente', function () {
        $this->getJson(route('produtos.show',9999))->assertNotFound();
    });
});

// ──────────────────────────────────────────────
// UPDATE
// ──────────────────────────────────────────────
describe('PUT /api/v1/produtos/{id}', function () {
    it('atualiza dados do produto', function () {
        $produto = Produto::factory()->create(['preco' => 100.00]);

        $response = $this->putJson(route('produtos.update',$produto->id), [
            'nome'  => 'Produto Atualizado',
            'preco' => 199.90,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.nome', 'Produto Atualizado')
            ->assertJsonPath('data.preco', 199.90);

        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'preco' => 199.90]);
    });

    it('pode desativar um produto', function () {
        $produto = Produto::factory()->create(['ativo' => true]);

        $this->putJson(route('produtos.update',$produto->id), [
            'nome'  => $produto->nome,
            'preco' => $produto->preco,
            'ativo' => false,
        ])->assertOk();

        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'ativo' => false]);
    });
});

// ──────────────────────────────────────────────
// DELETE
// ──────────────────────────────────────────────
describe('DELETE /api/v1/produtos/{id}', function () {
    it('remove produto com soft delete', function () {
        $produto = Produto::factory()->create();

        $response = $this->deleteJson(route('produtos.destroy',$produto->id));

        $response->assertOk()->assertJsonPath('message', 'Produto removido com sucesso.');
        $this->assertSoftDeleted('produtos', ['id' => $produto->id]);
    });

    it('retorna 404 ao tentar remover produto inexistente', function () {
        $this->deleteJson(route('produtos.destroy',9999))->assertNotFound();
    });
});