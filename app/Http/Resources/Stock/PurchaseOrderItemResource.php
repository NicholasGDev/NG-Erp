<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderItemResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, purchase_order_id: int, product_id: int,
     *     product: ProductResource|null,
     *     quantity_ordered: float, quantity_received: float,
     *     expected_unit_cost: float, actual_unit_cost: float|null,
     *     subtotal: float
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => (int) $this->id,
            'purchase_order_id'   => (int) $this->purchase_order_id,
            'product_id'          => (int) $this->product_id,
            'product'             => new ProductResource($this->whenLoaded('product')),
            'quantity_ordered'    => (float) $this->quantity_ordered,
            'quantity_received'   => (float) $this->quantity_received,
            'expected_unit_cost'  => (float) $this->expected_unit_cost,
            'actual_unit_cost'    => $this->actual_unit_cost !== null ? (float) $this->actual_unit_cost : null,
            'subtotal'            => (float) $this->quantity_ordered * (float) $this->expected_unit_cost,
        ];
    }
}
