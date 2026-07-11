<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, name: string, type: string, address: string|null, active: bool,
     *     stock_positions: array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|null,
     *     created_at: string|null, updated_at: string|null
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => (int) $this->id,
            'name'           => (string) $this->name,
            'type'           => (string) $this->type,
            'address'        => $this->address !== null ? (string) $this->address : null,
            'active'         => (bool) $this->active,
            'stock_positions' => StockPositionResource::collection($this->whenLoaded('stockPositions')),
            'created_at'     => $this->created_at?->toISOString(),
            'updated_at'     => $this->updated_at?->toISOString(),
        ];
    }
}
