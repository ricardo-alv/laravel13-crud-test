<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pessoa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'telefone',
        'data_nascimento',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    protected $hidden = [];

   public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'pessoa_produto')
            ->withPivot(['quantidade', 'preco_unitario'])
            ->withTimestamps();
    }
}
