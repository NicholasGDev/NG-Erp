<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ProductService
{
    public function __construct(private readonly Product $model)
    {
    }

    public function index(string $search = ''): LengthAwarePaginator
    {
        return $this->model
            ->when($search !== '', static fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%"))
            ->paginate();
    }

    public function show(int $id): Product
    {
        return $this->model->with(['batches', 'stockMovements'])->findOrFail($id);
    }

    /**
     * @param array{
     *     sku: string, name: string, unit_of_measure: string,
     *     minimum_stock?: float, maximum_stock?: float|null, costing_method?: string,
     *     tracks_batch?: bool, active?: bool
     * } $data
     */
    public function store(array $data): Product
    {
        return $this->model->create($this->typedAttributes($data));
    }

    /**
     * @param array{
     *     name?: string, unit_of_measure?: string, minimum_stock?: float,
     *     maximum_stock?: float|null, tracks_batch?: bool, active?: bool
     * } $data
     */
    public function update(int $id, array $data): Product
    {
        $product = $this->model->findOrFail($id);
        $product->update($this->typedAttributes($data));
        return $product;
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

        if (array_key_exists('sku', $data)) {
            $attributes['sku'] = (string) $data['sku'];
        }
        if (array_key_exists('name', $data)) {
            $attributes['name'] = (string) $data['name'];
        }
        if (array_key_exists('unit_of_measure', $data)) {
            $attributes['unit_of_measure'] = (string) $data['unit_of_measure'];
        }
        if (array_key_exists('minimum_stock', $data)) {
            $attributes['minimum_stock'] = (float) $data['minimum_stock'];
        }
        if (array_key_exists('maximum_stock', $data)) {
            $attributes['maximum_stock'] = $data['maximum_stock'] !== null ? (float) $data['maximum_stock'] : null;
        }
        if (array_key_exists('costing_method', $data)) {
            $attributes['costing_method'] = (string) $data['costing_method'];
        }
        if (array_key_exists('tracks_batch', $data)) {
            $attributes['tracks_batch'] = (bool) $data['tracks_batch'];
        }
        if (array_key_exists('active', $data)) {
            $attributes['active'] = (bool) $data['active'];
        }

        return $attributes;
    }
}
