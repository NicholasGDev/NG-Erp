<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'sku', 'name', 'unit_of_measure', 'minimum_stock', 'maximum_stock',
        'costing_method', 'current_average_cost', 'tracks_batch', 'active',
    ];

    protected $casts = [
        'minimum_stock'         => 'decimal:3',
        'maximum_stock'         => 'decimal:3',
        'current_average_cost'  => 'decimal:4',
        'tracks_batch'          => 'boolean',
        'active'                => 'boolean',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class, 'product_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }
}
