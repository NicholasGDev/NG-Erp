<?php

declare(strict_types=1);

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tax_id'                  => 'required|string|size:18|unique:suppliers,tax_id',
            'legal_name'              => 'required|string|max:255',
            'delivery_lead_time_days' => 'integer|min:0',
            'default_payment_terms'   => 'nullable|string',
            'contact_email'           => 'nullable|email',
            'phone'                   => 'nullable|string|max:20',
            'active'                  => 'boolean',
        ];
    }
}
