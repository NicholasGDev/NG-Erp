<?php

declare(strict_types=1);

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Services\InventarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function __construct(protected InventarioService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->index());
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->service->store($request->validate([
            'armazem_id'             => 'required|exists:armazens,id',
            'data_inicio'            => 'required|date',
            'usuario_responsavel_id' => 'required|integer',
            'observacoes'            => 'nullable|string',
            'contagens'              => 'array',
            'contagens.*.produto_id'        => 'required|exists:produtos,id',
            'contagens.*.lote_id'           => 'nullable|exists:lotes,id',
            'contagens.*.quantidade_sistema'=> 'required|numeric|min:0',
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
            'status'     => 'sometimes|string|in:em_andamento,ajustado,cancelado',
            'data_fim'   => 'nullable|date',
            'observacoes'=> 'nullable|string',
        ]));
        return response()->json($item);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
