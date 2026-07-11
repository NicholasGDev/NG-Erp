<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'                  => 'sometimes|string|in:draft,issued,partially_received,completed,cancelled',
            'expected_delivery_date'  => 'nullable|date',
            'notes'                   => 'nullable|string',
        ];
    }
}
