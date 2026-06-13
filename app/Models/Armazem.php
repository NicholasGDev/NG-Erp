<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Armazem extends Model
{
    use SoftDeletes;

    protected $table = 'armazens';

    protected $fillable = ['nome', 'tipo', 'endereco', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function posicoes(): HasMany
    {
        return $this->hasMany(PosicaoEstoque::class, 'armazem_id');
    }

    public function movimentacoesOrigem(): HasMany
    {
        return $this->hasMany(MovimentacaoEstoque::class, 'armazem_origem_id');
    }

    public function movimentacoesDestino(): HasMany
    {
        return $this->hasMany(MovimentacaoEstoque::class, 'armazem_destino_id');
    }

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class, 'armazem_id');
    }
}
