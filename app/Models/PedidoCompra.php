<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PedidoCompra extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos_compra';

    protected $fillable = [
        'fornecedor_id', 'numero_pedido', 'data_emissao',
        'data_prevista_entrega', 'status', 'valor_total', 'observacoes',
    ];

    protected $casts = [
        'data_emissao'          => 'datetime',
        'data_prevista_entrega' => 'date',
        'valor_total'           => 'decimal:2',
    ];

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemPedidoCompra::class, 'pedido_compra_id');
    }
}
