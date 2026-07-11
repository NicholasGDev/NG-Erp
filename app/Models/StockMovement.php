<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Stock movements are immutable (Kardex) — never use update() or delete().
 */
class StockMovement extends Model
{
    protected $table = 'stock_movements';

    protected $fillable = [
        'product_id', 'batch_id', 'source_warehouse_id', 'destination_warehouse_id',
        'movement_type', 'quantity', 'movement_unit_cost',
        'balance_after_movement', 'occurred_at', 'user_id',
        'reference_document', 'notes',
    ];

    protected $casts = [
        'quantity'                => 'decimal:3',
        'movement_unit_cost'      => 'decimal:4',
        'balance_after_movement'  => 'decimal:3',
        'occurred_at'             => 'datetime',
    ];

    public function product(): BelongsTo    { return $this->belongsTo(Product::class, 'product_id'); }
    public function batch(): BelongsTo       { return $this->belongsTo(Batch::class, 'batch_id'); }
    public function sourceWarehouse(): BelongsTo      { return $this->belongsTo(Warehouse::class, 'source_warehouse_id'); }
    public function destinationWarehouse(): BelongsTo { return $this->belongsTo(Warehouse::class, 'destination_warehouse_id'); }
}
