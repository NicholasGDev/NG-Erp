<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'legal_name'              => 'sometimes|string|max:255',
            'delivery_lead_time_days' => 'sometimes|integer|min:0',
            'default_payment_terms'   => 'nullable|string',
            'contact_email'           => 'nullable|email',
            'phone'                   => 'nullable|string|max:20',
            'active'                  => 'boolean',
        ];
    }
}
