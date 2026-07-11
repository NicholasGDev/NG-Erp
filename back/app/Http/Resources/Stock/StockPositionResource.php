<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockPositionResource extends JsonResource
{
    /**
     * @return array{id: int, warehouse_id: int, aisle: string|null, shelf: string|null, level: string|null, status: string}
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => (int) $this->id,
            'warehouse_id' => (int) $this->warehouse_id,
            'aisle'        => $this->aisle !== null ? (string) $this->aisle : null,
            'shelf'        => $this->shelf !== null ? (string) $this->shelf : null,
            'level'        => $this->level !== null ? (string) $this->level : null,
            'status'       => (string) $this->status,
        ];
    }
}
