<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPedidoCompra extends Model
{
    protected $table = 'itens_pedido_compra';

    protected $fillable = [
        'pedido_compra_id', 'produto_id', 'quantidade_solicitada',
        'quantidade_recebida', 'custo_unitario_previsto', 'custo_unitario_real',
    ];

    protected $casts = [
        'quantidade_solicitada'   => 'decimal:3',
        'quantidade_recebida'     => 'decimal:3',
        'custo_unitario_previsto' => 'decimal:4',
        'custo_unitario_real'     => 'decimal:4',
    ];

    public function pedidoCompra(): BelongsTo
    {
        return $this->belongsTo(PedidoCompra::class, 'pedido_compra_id');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
