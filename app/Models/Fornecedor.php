<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fornecedor extends Model
{
    use SoftDeletes;

    protected $table = 'fornecedores';

    protected $fillable = [
        'cnpj', 'razao_social', 'prazo_entrega_dias',
        'condicao_pagamento_padrao', 'email_contato', 'telefone', 'ativo',
    ];

    protected $casts = [
        'prazo_entrega_dias' => 'integer',
        'ativo'              => 'boolean',
    ];

    public function pedidosCompra(): HasMany
    {
        return $this->hasMany(PedidoCompra::class, 'fornecedor_id');
    }
}
