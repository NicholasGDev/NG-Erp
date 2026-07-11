<?php

declare(strict_types=1);

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    /**
     * @return array{
     *     id: int, product_id: int, batch_number: string, manufactured_at: string|null,
     *     expires_at: string|null, status: string, available_quantity: float
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => (int) $this->id,
            'product_id'         => (int) $this->product_id,
            'batch_number'       => (string) $this->batch_number,
            'manufactured_at'    => $this->manufactured_at?->toDateString(),
            'expires_at'         => $this->expires_at?->toDateString(),
            'status'             => (string) $this->status,
            'available_quantity' => (float) $this->available_quantity,
        ];
    }
}
