<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PessoaRequest;
use App\Http\Resources\PessoaResource;
use App\Models\Pessoa;
use App\Services\PessoaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PessoaController extends Controller
{
    public function __construct(
        private readonly PessoaService $service
    ) {}

    /**
     * @OA\Get(path="/api/pessoas", ...)
     */
    public function index(): AnonymousResourceCollection
    {
        $pessoas = $this->service->paginate();

        return PessoaResource::collection($pessoas);
    }

    public function store(PessoaRequest $request): JsonResponse
    {
        $pessoa = $this->service->create($request->validated());

        return (new PessoaResource($pessoa))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Pessoa $pessoa): PessoaResource
    {
        $pessoa->load('produtos');

        return new PessoaResource($pessoa);
    }

    public function update(PessoaRequest $request, Pessoa $pessoa): PessoaResource
    {
        $pessoa = $this->service->update($pessoa, $request->validated());

        return new PessoaResource($pessoa);
    }

    public function destroy(Pessoa $pessoa): JsonResponse
    {
        $this->service->delete($pessoa);

        return response()->json(['message' => 'Pessoa removida com sucesso.']);
    }
}