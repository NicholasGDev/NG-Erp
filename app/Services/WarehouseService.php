<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class WarehouseService
{
    public function __construct(private readonly Warehouse $model)
    {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->model->with('stockPositions')->paginate();
    }

    public function show(int $id): Warehouse
    {
        return $this->model->with('stockPositions')->findOrFail($id);
    }

    /**
     * @param array{name: string, type: string, address?: string|null, active?: bool} $data
     */
    public function store(array $data): Warehouse
    {
        return $this->model->create($this->typedAttributes($data));
    }

    /**
     * @param array{name?: string, type?: string, address?: string|null, active?: bool} $data
     */
    public function update(int $id, array $data): Warehouse
    {
        $warehouse = $this->model->findOrFail($id);
        $warehouse->update($this->typedAttributes($data));
        return $warehouse;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    /**
     * @param array<string, mixed> $data
     * @return array{name?: string, type?: string, address?: string|null, active?: bool}
     */
    private function typedAttributes(array $data): array
    {
        $attributes = [];

        if (array_key_exists('name', $data)) {
            $attributes['name'] = (string) $data['name'];
        }
        if (array_key_exists('type', $data)) {
            $attributes['type'] = (string) $data['type'];
        }
        if (array_key_exists('address', $data)) {
            $attributes['address'] = $data['address'] !== null ? (string) $data['address'] : null;
        }
        if (array_key_exists('active', $data)) {
            $attributes['active'] = (bool) $data['active'];
        }

        return $attributes;
    }
}
