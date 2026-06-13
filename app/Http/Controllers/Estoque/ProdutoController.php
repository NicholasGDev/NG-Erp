<?php

declare(strict_types=1);

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Services\ProdutoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function __construct(protected ProdutoService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->index());
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->service->store($request->validate([
            'sku'            => 'required|string|max:100|unique:produtos,sku',
            'nome'           => 'required|string|max:255',
            'unidade_medida' => 'required|string|in:UN,KG,CX,LT,MT',
            'estoque_minimo' => 'numeric|min:0',
            'estoque_maximo' => 'nullable|numeric|min:0',
            'metodo_custo'   => 'in:PEPS,CUSTO_MEDIO',
            'controla_lote'  => 'boolean',
            'ativo'          => 'boolean',
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
            'nome'           => 'sometimes|string|max:255',
            'unidade_medida' => 'sometimes|string|in:UN,KG,CX,LT,MT',
            'estoque_minimo' => 'sometimes|numeric|min:0',
            'estoque_maximo' => 'nullable|numeric|min:0',
            'controla_lote'  => 'boolean',
            'ativo'          => 'boolean',
        ]));
        return response()->json($item);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
