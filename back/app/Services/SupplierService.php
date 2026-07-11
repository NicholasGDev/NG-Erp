<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class SupplierService
{
    public function __construct(private readonly Supplier $model)
    {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }

    public function show(int $id): Supplier
    {
        return $this->model->with('purchaseOrders')->findOrFail($id);
    }

    /**
     * @param array{
     *     tax_id: string, legal_name: string, delivery_lead_time_days?: int,
     *     default_payment_terms?: string|null, contact_email?: string|null,
     *     phone?: string|null, active?: bool
     * } $data
     */
    public function store(array $data): Supplier
    {
        return $this->model->create($this->typedAttributes($data));
    }

    /**
     * @param array{
     *     legal_name?: string, delivery_lead_time_days?: int,
     *     default_payment_terms?: string|null, contact_email?: string|null,
     *     phone?: string|null, active?: bool
     * } $data
     */
    public function update(int $id, array $data): Supplier
    {
        $supplier = $this->model->findOrFail($id);
        $supplier->update($this->typedAttributes($data));
        return $supplier;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function typedAttributes(array $data): array
    {
        $attributes = [];

        if (array_key_exists('tax_id', $data)) {
            $attributes['tax_id'] = (string) $data['tax_id'];
        }
        if (array_key_exists('legal_name', $data)) {
            $attributes['legal_name'] = (string) $data['legal_name'];
        }
        if (array_key_exists('delivery_lead_time_days', $data)) {
            $attributes['delivery_lead_time_days'] = (int) $data['delivery_lead_time_days'];
        }
        if (array_key_exists('default_payment_terms', $data)) {
            $attributes['default_payment_terms'] = $data['default_payment_terms'] !== null
                ? (string) $data['default_payment_terms']
                : null;
        }
        if (array_key_exists('contact_email', $data)) {
            $attributes['contact_email'] = $data['contact_email'] !== null ? (string) $data['contact_email'] : null;
        }
        if (array_key_exists('phone', $data)) {
            $attributes['phone'] = $data['phone'] !== null ? (string) $data['phone'] : null;
        }
        if (array_key_exists('active', $data)) {
            $attributes['active'] = (bool) $data['active'];
        }

        return $attributes;
    }
}
