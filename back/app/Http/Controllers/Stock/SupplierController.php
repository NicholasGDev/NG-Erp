<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreSupplierRequest;
use App\Http\Requests\Stock\UpdateSupplierRequest;
use App\Http\Resources\Stock\SupplierResource;
use App\Services\SupplierService;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $service) {}

    public function index(): JsonResponse
    {
        return response()->json(SupplierResource::collection($this->service->index()));
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $item = $this->service->store($request->validated());
        return response()->json(new SupplierResource($item), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(new SupplierResource($this->service->show($id)));
    }

    public function update(UpdateSupplierRequest $request, int $id): JsonResponse
    {
        $item = $this->service->update($id, $request->validated());
        return response()->json(new SupplierResource($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
