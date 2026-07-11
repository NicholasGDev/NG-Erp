<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PhysicalInventory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PhysicalInventoryService
{
    public function __construct(private readonly PhysicalInventory $model)
    {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->model->with('warehouse')->paginate();
    }

    public function show(int $id): PhysicalInventory
    {
        return $this->model->with(['warehouse', 'counts.product'])->findOrFail($id);
    }

    /**
     * @param array{
     *     warehouse_id: int, started_at: string, notes?: string|null,
     *     counts?: array<int, array{product_id: int, batch_id?: int|null, system_quantity: float}>
     * } $data
     */
    public function store(array $data, int $responsibleUserId): PhysicalInventory
    {
        $counts = $data['counts'] ?? [];

        $physicalInventory = $this->model->create([
            'warehouse_id'          => (int) $data['warehouse_id'],
            'started_at'            => (string) $data['started_at'],
            'status'                => 'in_progress',
            'responsible_user_id'   => $responsibleUserId,
            'notes'                 => isset($data['notes']) ? (string) $data['notes'] : null,
        ]);

        if ($counts !== []) {
            $physicalInventory->counts()->createMany(array_map(
                static fn (array $count): array => [
                    'product_id'        => (int) $count['product_id'],
                    'batch_id'          => isset($count['batch_id']) ? (int) $count['batch_id'] : null,
                    'system_quantity'   => (float) $count['system_quantity'],
                ],
                $counts
            ));
        }

        return $physicalInventory->load(['warehouse', 'counts']);
    }

    /**
     * @param array{status?: string, finished_at?: string|null, notes?: string|null} $data
     */
    public function update(int $id, array $data): PhysicalInventory
    {
        $physicalInventory = $this->model->findOrFail($id);
        $physicalInventory->update($this->typedAttributes($data));
        return $physicalInventory;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function typedAttributes(array $data): array
    {
        $attributes = [];

        if (array_key_exists('status', $data)) {
            $attributes['status'] = (string) $data['status'];
        }
        if (array_key_exists('finished_at', $data)) {
            $attributes['finished_at'] = $data['finished_at'] !== null ? (string) $data['finished_at'] : null;
        }
        if (array_key_exists('notes', $data)) {
            $attributes['notes'] = $data['notes'] !== null ? (string) $data['notes'] : null;
        }

        return $attributes;
    }
}
