<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockPosition extends Model
{
    protected $table = 'stock_positions';

    protected $fillable = ['warehouse_id', 'aisle', 'shelf', 'level', 'status'];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
