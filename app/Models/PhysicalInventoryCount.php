<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalInventoryCount extends Model
{
    protected $table = 'physical_inventory_counts';

    protected $fillable = [
        'physical_inventory_id', 'product_id', 'batch_id',
        'system_quantity', 'physical_quantity', 'variance', 'adjustment_applied',
    ];

    protected $casts = [
        'system_quantity'    => 'decimal:3',
        'physical_quantity'  => 'decimal:3',
        'variance'           => 'decimal:3',
        'adjustment_applied' => 'boolean',
    ];

    public function physicalInventory(): BelongsTo { return $this->belongsTo(PhysicalInventory::class, 'physical_inventory_id'); }
    public function product(): BelongsTo           { return $this->belongsTo(Product::class, 'product_id'); }
    public function batch(): BelongsTo             { return $this->belongsTo(Batch::class, 'batch_id'); }
}
