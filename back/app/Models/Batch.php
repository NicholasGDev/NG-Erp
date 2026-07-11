<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batch extends Model
{
    protected $table = 'batches';

    protected $fillable = [
        'product_id', 'batch_number', 'manufactured_at',
        'expires_at', 'status', 'available_quantity',
    ];

    protected $casts = [
        'manufactured_at'     => 'date',
        'expires_at'          => 'date',
        'available_quantity'  => 'decimal:3',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
