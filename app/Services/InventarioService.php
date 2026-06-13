<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Inventario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventarioService
{
    public function __construct(protected Inventario $model) {}

    public function index(): LengthAwarePaginator
    {
        return $this->model->with('armazem')->paginate();
    }

    public function show(int $id): Inventario
    {
        return $this->model->with(['armazem', 'contagens.produto'])->findOrFail($id);
    }

    public function store(array $data): Inventario
    {
        $contagens = $data['contagens'] ?? [];
        unset($data['contagens']);

        $inventario = $this->model->create($data);

        if (!empty($contagens)) {
            $inventario->contagens()->createMany($contagens);
        }

        return $inventario->load(['armazem', 'contagens']);
    }

    public function update(int $id, array $data): Inventario
    {
        $inventario = $this->model->findOrFail($id);
        $inventario->update($data);
        return $inventario;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }
}
