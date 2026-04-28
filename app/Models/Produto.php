<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'estoque',
        'categoria',
        'ativo',
    ];

    protected $casts = [
        'preco'  => 'decimal:2',
        'ativo'  => 'boolean',
        'estoque' => 'integer',
    ];

    public function pessoas(): BelongsToMany
    {
        return $this->belongsToMany(Pessoa::class, 'pessoa_produto')
            ->withPivot(['quantidade', 'preco_unitario'])
            ->withTimestamps();
    }
}
