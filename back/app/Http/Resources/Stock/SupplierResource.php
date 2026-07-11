<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, tax_id: string, legal_name: string, delivery_lead_time_days: int,
     *     default_payment_terms: string|null, contact_email: string|null,
     *     phone: string|null, active: bool, created_at: string|null, updated_at: string|null
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => (int) $this->id,
            'tax_id'                => (string) $this->tax_id,
            'legal_name'            => (string) $this->legal_name,
            'delivery_lead_time_days' => (int) $this->delivery_lead_time_days,
            'default_payment_terms' => $this->default_payment_terms !== null ? (string) $this->default_payment_terms : null,
            'contact_email'         => $this->contact_email !== null ? (string) $this->contact_email : null,
            'phone'                 => $this->phone !== null ? (string) $this->phone : null,
            'active'                => (bool) $this->active,
            'created_at'            => $this->created_at?->toISOString(),
            'updated_at'            => $this->updated_at?->toISOString(),
        ];
    }
}
