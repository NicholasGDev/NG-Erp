<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class PurchaseOrderService
{
    public function __construct(private readonly PurchaseOrder $model)
    {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->model->with('supplier')->paginate();
    }

    public function show(int $id): PurchaseOrder
    {
        return $this->model->with(['supplier', 'items.product'])->findOrFail($id);
    }

    /**
     * @param array{
     *     supplier_id: int, order_number?: string|null, issued_at: string,
     *     expected_delivery_date?: string|null, notes?: string|null,
     *     items?: array<int, array{product_id: int, quantity_ordered: float, expected_unit_cost: float}>
     * } $data
     */
    public function store(array $data): PurchaseOrder
    {
        $items = array_map(
            static fn (array $item): array => [
                'product_id'          => (int) $item['product_id'],
                'quantity_ordered'    => (float) $item['quantity_ordered'],
                'expected_unit_cost'  => (float) $item['expected_unit_cost'],
            ],
            $data['items'] ?? []
        );

        return DB::transaction(function () use ($data, $items): PurchaseOrder {
            $order = $this->model->create([
                'supplier_id'             => (int) $data['supplier_id'],
                'order_number'            => $this->resolveOrderNumber($data['order_number'] ?? null),
                'issued_at'               => (string) $data['issued_at'],
                'expected_delivery_date'  => isset($data['expected_delivery_date']) ? (string) $data['expected_delivery_date'] : null,
                'status'                  => 'draft',
                'total_amount'            => $this->calculateTotalAmount($items),
                'notes'                   => isset($data['notes']) ? (string) $data['notes'] : null,
            ]);

            if ($items !== []) {
                $order->items()->createMany($items);
            }

            return $order->load(['supplier', 'items.product']);
        });
    }

    /**
     * @param array{status?: string, expected_delivery_date?: string|null, notes?: string|null} $data
     */
    public function update(int $id, array $data): PurchaseOrder
    {
        $order = $this->model->findOrFail($id);
        $order->update($this->typedAttributes($data));
        return $order;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    /**
     * @param array<int, array{quantity_ordered: float, expected_unit_cost: float}> $items
     */
    private function calculateTotalAmount(array $items): float
    {
        return array_sum(array_map(
            static fn (array $item): float => $item['quantity_ordered'] * $item['expected_unit_cost'],
            $items
        ));
    }

    private function resolveOrderNumber(?string $orderNumber): string
    {
        if ($orderNumber !== null && $orderNumber !== '') {
            return $orderNumber;
        }

        return 'PO-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function typedAttributes(array $data): array
    {
        $attributes = [];

        if (array_key_exists('status', $data)) {
            $attributes['status'] = (string) $data['status'];
        }
        if (array_key_exists('expected_delivery_date', $data)) {
            $attributes['expected_delivery_date'] = $data['expected_delivery_date'] !== null
                ? (string) $data['expected_delivery_date']
                : null;
        }
        if (array_key_exists('notes', $data)) {
            $attributes['notes'] = $data['notes'] !== null ? (string) $data['notes'] : null;
        }

        return $attributes;
    }
}
