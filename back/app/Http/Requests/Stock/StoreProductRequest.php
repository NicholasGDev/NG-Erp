<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku'             => 'required|string|max:100|unique:products,sku',
            'name'            => 'required|string|max:255',
            'unit_of_measure' => 'required|string|in:UN,KG,CX,LT,MT',
            'minimum_stock'   => 'numeric|min:0',
            'maximum_stock'   => 'nullable|numeric|min:0',
            'costing_method'  => 'in:FIFO,AVERAGE_COST',
            'tracks_batch'    => 'boolean',
            'active'          => 'boolean',
        ];
    }
}
