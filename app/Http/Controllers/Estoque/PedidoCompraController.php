<?php

declare(strict_types=1);

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Services\PedidoCompraService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PedidoCompraController extends Controller
{
    public function __construct(protected PedidoCompraService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->index());
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->service->store($request->validate([
            'fornecedor_id'         => 'required|exists:fornecedores,id',
            'numero_pedido'         => 'nullable|string|unique:pedidos_compra,numero_pedido',
            'data_emissao'          => 'required|date',
            'data_prevista_entrega' => 'nullable|date|after:data_emissao',
            'observacoes'           => 'nullable|string',
            'itens'                 => 'array',
            'itens.*.produto_id'               => 'required|exists:produtos,id',
            'itens.*.quantidade_solicitada'    => 'required|numeric|min:0.001',
            'itens.*.custo_unitario_previsto'  => 'required|numeric|min:0',
        ]));
        return response()->json($item, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->service->show($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = $this->service->update($id, $request->validate([
            'status'                => 'sometimes|string|in:rascunho,emitido,recebido_parcial,concluido,cancelado',
            'data_prevista_entrega' => 'nullable|date',
            'observacoes'           => 'nullable|string',
        ]));
        return response()->json($item);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
