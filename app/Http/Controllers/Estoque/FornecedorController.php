<?php

declare(strict_types=1);

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Services\FornecedorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function __construct(protected FornecedorService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->index());
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->service->store($request->validate([
            'cnpj'                       => 'required|string|size:18|unique:fornecedores,cnpj',
            'razao_social'               => 'required|string|max:255',
            'prazo_entrega_dias'         => 'integer|min:0',
            'condicao_pagamento_padrao'  => 'nullable|string',
            'email_contato'              => 'nullable|email',
            'telefone'                   => 'nullable|string|max:20',
            'ativo'                      => 'boolean',
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
            'razao_social'              => 'sometimes|string|max:255',
            'prazo_entrega_dias'        => 'sometimes|integer|min:0',
            'condicao_pagamento_padrao' => 'nullable|string',
            'email_contato'             => 'nullable|email',
            'telefone'                  => 'nullable|string|max:20',
            'ativo'                     => 'boolean',
        ]));
        return response()->json($item);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
