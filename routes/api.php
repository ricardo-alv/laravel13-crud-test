<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    ProdutoController,
    PessoaController
};

Route::prefix('v1')->group(function () { 
    // Recursos de Pessoas
    Route::apiResource('pessoas', PessoaController::class);
    // Recursos de Produtos
    Route::apiResource('produtos', ProdutoController::class);
});
 