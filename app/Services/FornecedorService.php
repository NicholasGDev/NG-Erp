<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Fornecedor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FornecedorService
{
    public function __construct(protected Fornecedor $model) {}

    public function index(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }

    public function show(int $id): Fornecedor
    {
        return $this->model->with('pedidosCompra')->findOrFail($id);
    }

    public function store(array $data): Fornecedor
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Fornecedor
    {
        $fornecedor = $this->model->findOrFail($id);
        $fornecedor->update($data);
        return $fornecedor;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }
}
