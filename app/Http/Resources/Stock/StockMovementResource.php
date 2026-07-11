<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, product_id: int, product: ProductResource|null,
     *     batch_id: int|null, source_warehouse_id: int|null, destination_warehouse_id: int|null,
     *     movement_type: string, quantity: float, movement_unit_cost: float,
     *     balance_after_movement: float, occurred_at: string|null, user_id: int,
     *     reference_document: string|null, notes: string|null
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                      => (int) $this->id,
            'product_id'              => (int) $this->product_id,
            'product'                 => new ProductResource($this->whenLoaded('product')),
            'batch_id'                => $this->batch_id !== null ? (int) $this->batch_id : null,
            'source_warehouse_id'     => $this->source_warehouse_id !== null ? (int) $this->source_warehouse_id : null,
            'destination_warehouse_id' => $this->destination_warehouse_id !== null ? (int) $this->destination_warehouse_id : null,
            'movement_type'           => (string) $this->movement_type,
            'quantity'                => (float) $this->quantity,
            'movement_unit_cost'      => (float) $this->movement_unit_cost,
            'balance_after_movement'  => (float) $this->balance_after_movement,
            'occurred_at'             => $this->occurred_at?->toISOString(),
            'user_id'                 => (int) $this->user_id,
            'reference_document'      => $this->reference_document !== null ? (string) $this->reference_document : null,
            'notes'                   => $this->notes !== null ? (string) $this->notes : null,
        ];
    }
}
