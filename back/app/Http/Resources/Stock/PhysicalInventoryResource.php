<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhysicalInventoryResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, warehouse_id: int, warehouse: WarehouseResource|null,
     *     started_at: string|null, finished_at: string|null, status: string,
     *     responsible_user_id: int, notes: string|null,
     *     counts: array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|null
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => (int) $this->id,
            'warehouse_id'         => (int) $this->warehouse_id,
            'warehouse'            => new WarehouseResource($this->whenLoaded('warehouse')),
            'started_at'           => $this->started_at?->toISOString(),
            'finished_at'          => $this->finished_at?->toISOString(),
            'status'               => (string) $this->status,
            'responsible_user_id'  => (int) $this->responsible_user_id,
            'notes'                => $this->notes !== null ? (string) $this->notes : null,
            'counts'               => PhysicalInventoryCountResource::collection($this->whenLoaded('counts')),
        ];
    }
}
