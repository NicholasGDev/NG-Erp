<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, sku: string, name: string, unit_of_measure: string,
     *     minimum_stock: float, maximum_stock: float|null, costing_method: string,
     *     current_average_cost: float, tracks_batch: bool, active: bool,
     *     batches: array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|null,
     *     stock_movements: array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|null,
     *     created_at: string|null, updated_at: string|null
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => (int) $this->id,
            'sku'                  => (string) $this->sku,
            'name'                 => (string) $this->name,
            'unit_of_measure'      => (string) $this->unit_of_measure,
            'minimum_stock'        => (float) $this->minimum_stock,
            'maximum_stock'        => $this->maximum_stock !== null ? (float) $this->maximum_stock : null,
            'costing_method'       => (string) $this->costing_method,
            'current_average_cost' => (float) $this->current_average_cost,
            'tracks_batch'         => (bool) $this->tracks_batch,
            'active'               => (bool) $this->active,
            'batches'              => BatchResource::collection($this->whenLoaded('batches')),
            'stock_movements'      => StockMovementResource::collection($this->whenLoaded('stockMovements')),
            'created_at'           => $this->created_at?->toISOString(),
            'updated_at'           => $this->updated_at?->toISOString(),
        ];
    }
}
