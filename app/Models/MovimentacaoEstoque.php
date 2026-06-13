<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Movimentações são imutáveis (Kardex) — nunca usar update() ou delete().
 */
class MovimentacaoEstoque extends Model
{
    protected $table = 'movimentacoes_estoque';

    protected $fillable = [
        'produto_id', 'lote_id', 'armazem_origem_id', 'armazem_destino_id',
        'tipo_movimento', 'quantidade', 'custo_unitario_movimento',
        'saldo_apos_movimento', 'data_hora', 'usuario_id',
        'documento_referencia', 'observacao',
    ];

    protected $casts = [
        'quantidade'               => 'decimal:3',
        'custo_unitario_movimento' => 'decimal:4',
        'saldo_apos_movimento'     => 'decimal:3',
        'data_hora'                => 'datetime',
    ];

    public function produto(): BelongsTo    { return $this->belongsTo(Produto::class, 'produto_id'); }
    public function lote(): BelongsTo       { return $this->belongsTo(Lote::class, 'lote_id'); }
    public function armazemOrigem(): BelongsTo  { return $this->belongsTo(Armazem::class, 'armazem_origem_id'); }
    public function armazemDestino(): BelongsTo { return $this->belongsTo(Armazem::class, 'armazem_destino_id'); }
}
