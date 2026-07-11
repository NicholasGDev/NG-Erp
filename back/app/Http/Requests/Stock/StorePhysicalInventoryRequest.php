<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StorePhysicalInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_id'                   => 'required|exists:warehouses,id',
            'started_at'                     => 'required|date',
            'notes'                          => 'nullable|string',
            'counts'                         => 'array',
            'counts.*.product_id'            => 'required|exists:products,id',
            'counts.*.batch_id'              => 'nullable|exists:batches,id',
            'counts.*.system_quantity'       => 'required|numeric|min:0',
        ];
    }
}
