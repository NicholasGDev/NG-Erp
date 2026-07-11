<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = 'warehouses';

    protected $fillable = ['name', 'type', 'address', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function stockPositions(): HasMany
    {
        return $this->hasMany(StockPosition::class, 'warehouse_id');
    }

    public function sourceStockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'source_warehouse_id');
    }

    public function destinationStockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'destination_warehouse_id');
    }

    public function physicalInventories(): HasMany
    {
        return $this->hasMany(PhysicalInventory::class, 'warehouse_id');
    }
}
