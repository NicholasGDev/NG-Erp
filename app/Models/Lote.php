<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lote extends Model
{
    protected $table = 'lotes';

    protected $fillable = [
        'produto_id', 'numero_lote', 'data_fabricacao',
        'data_validade', 'status', 'quantidade_disponivel',
    ];

    protected $casts = [
        'data_fabricacao'       => 'date',
        'data_validade'         => 'date',
        'quantidade_disponivel' => 'decimal:3',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
