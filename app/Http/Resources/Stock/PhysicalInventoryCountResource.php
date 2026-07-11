<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhysicalInventoryCountResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, physical_inventory_id: int, product_id: int, product: ProductResource|null,
     *     batch_id: int|null, system_quantity: float, physical_quantity: float|null,
     *     variance: float|null, adjustment_applied: bool
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => (int) $this->id,
            'physical_inventory_id' => (int) $this->physical_inventory_id,
            'product_id'            => (int) $this->product_id,
            'product'               => new ProductResource($this->whenLoaded('product')),
            'batch_id'              => $this->batch_id !== null ? (int) $this->batch_id : null,
            'system_quantity'       => (float) $this->system_quantity,
            'physical_quantity'     => $this->physical_quantity !== null ? (float) $this->physical_quantity : null,
            'variance'              => $this->variance !== null ? (float) $this->variance : null,
            'adjustment_applied'    => (bool) $this->adjustment_applied,
        ];
    }
}
