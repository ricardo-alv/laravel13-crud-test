<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProdutoController extends Controller
{
    public function __construct(
        private readonly ProdutoService $service
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $produtos = $this->service->paginate();

        return ProdutoResource::collection($produtos);
    }

    public function store(ProdutoRequest $request): JsonResponse
    {
        $produto = $this->service->create($request->validated());

        return (new ProdutoResource($produto))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Produto $produto): ProdutoResource
    {
        $produto->load('pessoas');

        return new ProdutoResource($produto);
    }

    public function update(ProdutoRequest $request, Produto $produto): ProdutoResource
    {
        $produto = $this->service->update($produto, $request->validated());

        return new ProdutoResource($produto);
    }

    public function destroy(Produto $produto): JsonResponse
    {
        $this->service->delete($produto);

        return response()->json(['message' => 'Produto removido com sucesso.']);
    }
}