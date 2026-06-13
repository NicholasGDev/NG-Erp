<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use SoftDeletes;

    protected $table = 'produtos';

    protected $fillable = [
        'sku', 'nome', 'unidade_medida', 'estoque_minimo', 'estoque_maximo',
        'metodo_custo', 'custo_medio_atual', 'controla_lote', 'ativo',
    ];

    protected $casts = [
        'estoque_minimo'    => 'decimal:3',
        'estoque_maximo'    => 'decimal:3',
        'custo_medio_atual' => 'decimal:4',
        'controla_lote'     => 'boolean',
        'ativo'             => 'boolean',
    ];

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'produto_id');
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(MovimentacaoEstoque::class, 'produto_id');
    }
}
