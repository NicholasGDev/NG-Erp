<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreWarehouseRequest;
use App\Http\Requests\Stock\UpdateWarehouseRequest;
use App\Http\Resources\Stock\WarehouseResource;
use App\Services\WarehouseService;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function __construct(protected WarehouseService $service) {}

    public function index(): JsonResponse
    {
        return response()->json(WarehouseResource::collection($this->service->index()));
    }

    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        $item = $this->service->store($request->validated());
        return response()->json(new WarehouseResource($item), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(new WarehouseResource($this->service->show($id)));
    }

    public function update(UpdateWarehouseRequest $request, int $id): JsonResponse
    {
        $item = $this->service->update($id, $request->validated());
        return response()->json(new WarehouseResource($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
