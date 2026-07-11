<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = [
        'tax_id', 'legal_name', 'delivery_lead_time_days',
        'default_payment_terms', 'contact_email', 'phone', 'active',
    ];

    protected $casts = [
        'delivery_lead_time_days' => 'integer',
        'active'                  => 'boolean',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }
}
