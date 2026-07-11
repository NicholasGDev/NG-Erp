<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, supplier_id: int, supplier: SupplierResource|null,
     *     order_number: string|null, issued_at: string|null,
     *     expected_delivery_date: string|null, status: string, total_amount: float,
     *     notes: string|null,
     *     items: array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|null,
     *     created_at: string|null, updated_at: string|null
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => (int) $this->id,
            'supplier_id'            => (int) $this->supplier_id,
            'supplier'               => new SupplierResource($this->whenLoaded('supplier')),
            'order_number'           => $this->order_number !== null ? (string) $this->order_number : null,
            'issued_at'              => $this->issued_at?->toISOString(),
            'expected_delivery_date' => $this->expected_delivery_date?->toDateString(),
            'status'                 => (string) $this->status,
            'total_amount'           => (float) $this->total_amount,
            'notes'                  => $this->notes !== null ? (string) $this->notes : null,
            'items'                  => PurchaseOrderItemResource::collection($this->whenLoaded('items')),
            'created_at'             => $this->created_at?->toISOString(),
            'updated_at'             => $this->updated_at?->toISOString(),
        ];
    }
}
