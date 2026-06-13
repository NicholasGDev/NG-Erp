<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContagemInventario extends Model
{
    protected $table = 'contagens_inventario';

    protected $fillable = [
        'inventario_id', 'produto_id', 'lote_id',
        'quantidade_sistema', 'quantidade_fisica', 'divergencia', 'ajuste_aplicado',
    ];

    protected $casts = [
        'quantidade_sistema' => 'decimal:3',
        'quantidade_fisica'  => 'decimal:3',
        'divergencia'        => 'decimal:3',
        'ajuste_aplicado'    => 'boolean',
    ];

    public function inventario(): BelongsTo { return $this->belongsTo(Inventario::class, 'inventario_id'); }
    public function produto(): BelongsTo    { return $this->belongsTo(Produto::class, 'produto_id'); }
    public function lote(): BelongsTo       { return $this->belongsTo(Lote::class, 'lote_id'); }
}
