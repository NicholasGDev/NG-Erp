<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Kardex — stock movements are immutable.
 * Never expose update() or destroy().
 */
final class StockMovementService
{
    private const OUTBOUND_TYPES = ['sale_out', 'loss_adjustment'];

    public function __construct(
        private readonly StockMovement $model,
        private readonly Product $product,
    ) {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->model
            ->with(['product', 'sourceWarehouse', 'destinationWarehouse'])
            ->orderByDesc('occurred_at')
            ->paginate();
    }

    public function forProduct(int $productId): LengthAwarePaginator
    {
        return $this->model
            ->where('product_id', $productId)
            ->orderByDesc('occurred_at')
            ->paginate();
    }

    public function show(int $id): StockMovement
    {
        return $this->model
            ->with(['product', 'batch', 'sourceWarehouse', 'destinationWarehouse'])
            ->findOrFail($id);
    }

    /**
     * @param array{
     *     product_id: int, batch_id?: int|null, source_warehouse_id?: int|null,
     *     destination_warehouse_id?: int|null, movement_type: string, quantity: float,
     *     movement_unit_cost?: float, reference_document?: string|null,
     *     notes?: string|null
     * } $data
     */
    public function register(array $data, int $userId): StockMovement
    {
        $productId     = (int) $data['product_id'];
        $movementType  = (string) $data['movement_type'];
        $quantity      = (float) $data['quantity'];

        return DB::transaction(function () use ($data, $productId, $movementType, $quantity, $userId): StockMovement {
            $product = $this->product->lockForUpdate()->findOrFail($productId);

            $balance = $this->calculateBalance($product->id, $movementType, $quantity);

            $movementUnitCost = isset($data['movement_unit_cost'])
                ? (float) $data['movement_unit_cost']
                : (float) $product->current_average_cost;

            // Updates average cost on inbound movements
            if (str_ends_with($movementType, '_in')) {
                $product->update(['current_average_cost' => $movementUnitCost]);
            }

            return $this->model->create([
                'product_id'                => $productId,
                'batch_id'                  => isset($data['batch_id']) ? (int) $data['batch_id'] : null,
                'source_warehouse_id'       => isset($data['source_warehouse_id']) ? (int) $data['source_warehouse_id'] : null,
                'destination_warehouse_id'  => isset($data['destination_warehouse_id']) ? (int) $data['destination_warehouse_id'] : null,
                'movement_type'             => $movementType,
                'quantity'                  => $quantity,
                'movement_unit_cost'        => $movementUnitCost,
                'balance_after_movement'    => $balance,
                'occurred_at'               => now(),
                'user_id'                   => $userId,
                'reference_document'        => isset($data['reference_document']) ? (string) $data['reference_document'] : null,
                'notes'                     => isset($data['notes']) ? (string) $data['notes'] : null,
            ]);
        });
    }

    private function calculateBalance(int $productId, string $type, float $quantity): float
    {
        $lastBalance = (float) ($this->model
            ->where('product_id', $productId)
            ->orderByDesc('occurred_at')
            ->value('balance_after_movement') ?? 0.0);

        return in_array($type, self::OUTBOUND_TYPES, true)
            ? $lastBalance - $quantity
            : $lastBalance + $quantity;
    }
}
