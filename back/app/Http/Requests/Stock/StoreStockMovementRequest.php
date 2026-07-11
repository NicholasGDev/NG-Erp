<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'               => 'required|exists:products,id',
            'batch_id'                 => 'nullable|exists:batches,id',
            'source_warehouse_id'      => 'nullable|exists:warehouses,id',
            'destination_warehouse_id' => 'nullable|exists:warehouses,id',
            'movement_type'            => 'required|string|in:purchase_in,sale_out,transfer,loss_adjustment,gain_adjustment,return',
            'quantity'                 => 'required|numeric|min:0.001',
            'movement_unit_cost'       => 'numeric|min:0',
            'reference_document'       => 'nullable|string',
            'notes'                    => 'nullable|string',
        ];
    }
}
