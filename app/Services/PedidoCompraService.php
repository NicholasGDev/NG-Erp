<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PedidoCompra;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PedidoCompraService
{
    public function __construct(protected PedidoCompra $model) {}

    public function index(): LengthAwarePaginator
    {
        return $this->model->with('fornecedor')->paginate();
    }

    public function show(int $id): PedidoCompra
    {
        return $this->model->with(['fornecedor', 'itens.produto'])->findOrFail($id);
    }

    public function store(array $data): PedidoCompra
    {
        $itens = $data['itens'] ?? [];
        unset($data['itens']);

        $pedido = $this->model->create($data);

        if (!empty($itens)) {
            $pedido->itens()->createMany($itens);
        }

        return $pedido->load(['fornecedor', 'itens.produto']);
    }

    public function update(int $id, array $data): PedidoCompra
    {
        $pedido = $this->model->findOrFail($id);
        $pedido->update($data);
        return $pedido;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }
}
