<?php

declare(strict_types=1);

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Services\ArmazemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArmazemController extends Controller
{
    public function __construct(protected ArmazemService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->index());
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->service->store($request->validate([
            'nome'     => 'required|string|max:255',
            'tipo'     => 'required|string|in:loja,deposito_central,terceiros',
            'endereco' => 'nullable|string',
            'ativo'    => 'boolean',
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
            'nome'     => 'sometimes|string|max:255',
            'tipo'     => 'sometimes|string|in:loja,deposito_central,terceiros',
            'endereco' => 'nullable|string',
            'ativo'    => 'boolean',
        ]));
        return response()->json($item);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
