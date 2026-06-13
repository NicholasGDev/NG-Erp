<?php

declare(strict_types=1);

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Services\MovimentacaoEstoqueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Kardex — apenas index, show e store. Sem update ou destroy.
 */
class MovimentacaoEstoqueController extends Controller
{
    public function __construct(protected MovimentacaoEstoqueService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->index());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->service->show($id));
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->service->registrar($request->validate([
            'produto_id'               => 'required|exists:produtos,id',
            'lote_id'                  => 'nullable|exists:lotes,id',
            'armazem_origem_id'        => 'nullable|exists:armazens,id',
            'armazem_destino_id'       => 'nullable|exists:armazens,id',
            'tipo_movimento'           => 'required|string|in:entrada_compra,saida_venda,transferencia,ajuste_perda,ajuste_ganho,devolucao',
            'quantidade'               => 'required|numeric|min:0.001',
            'custo_unitario_movimento' => 'numeric|min:0',
            'usuario_id'               => 'required|integer',
            'documento_referencia'     => 'nullable|string',
            'observacao'               => 'nullable|string',
        ]));
        return response()->json($item, 201);
    }
}
