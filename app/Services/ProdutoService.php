<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Produto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProdutoService
{
    public function __construct(protected Produto $model) {}

    public function index(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }

    public function show(int $id): Produto
    {
        return $this->model->with(['lotes', 'movimentacoes'])->findOrFail($id);
    }

    public function store(array $data): Produto
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Produto
    {
        $produto = $this->model->findOrFail($id);
        $produto->update($data);
        return $produto;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }
}
