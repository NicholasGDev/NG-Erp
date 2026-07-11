<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'sometimes|string|max:255',
            'unit_of_measure' => 'sometimes|string|in:UN,KG,CX,LT,MT',
            'minimum_stock'   => 'sometimes|numeric|min:0',
            'maximum_stock'   => 'nullable|numeric|min:0',
            'tracks_batch'    => 'boolean',
            'active'          => 'boolean',
        ];
    }
}
