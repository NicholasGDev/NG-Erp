<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhysicalInventory extends Model
{
    protected $table = 'physical_inventories';

    protected $fillable = [
        'warehouse_id', 'started_at', 'finished_at',
        'status', 'responsible_user_id', 'notes',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function counts(): HasMany
    {
        return $this->hasMany(PhysicalInventoryCount::class, 'physical_inventory_id');
    }
}
