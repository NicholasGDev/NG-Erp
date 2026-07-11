<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'                      => 'required|exists:suppliers,id',
            'order_number'                     => 'nullable|string|unique:purchase_orders,order_number',
            'issued_at'                        => 'required|date',
            'expected_delivery_date'           => 'nullable|date|after:issued_at',
            'notes'                            => 'nullable|string',
            'items'                            => 'array',
            'items.*.product_id'               => 'required|exists:products,id',
            'items.*.quantity_ordered'         => 'required|numeric|min:0.001',
            'items.*.expected_unit_cost'       => 'required|numeric|min:0',
        ];
    }
}
