<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'type'    => 'required|string|in:store,central_warehouse,third_party',
            'address' => 'nullable|string',
            'active'  => 'boolean',
        ];
    }
}
