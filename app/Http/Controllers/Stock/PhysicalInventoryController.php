<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StorePhysicalInventoryRequest;
use App\Http\Requests\Stock\UpdatePhysicalInventoryRequest;
use App\Http\Resources\Stock\PhysicalInventoryResource;
use App\Services\PhysicalInventoryService;
use Illuminate\Http\JsonResponse;

class PhysicalInventoryController extends Controller
{
    public function __construct(protected PhysicalInventoryService $service) {}

    public function index(): JsonResponse
    {
        return response()->json(PhysicalInventoryResource::collection($this->service->index()));
    }

    public function store(StorePhysicalInventoryRequest $request): JsonResponse
    {
        $item = $this->service->store($request->validated(), (int) auth('api')->id());
        return response()->json(new PhysicalInventoryResource($item), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(new PhysicalInventoryResource($this->service->show($id)));
    }

    public function update(UpdatePhysicalInventoryRequest $request, int $id): JsonResponse
    {
        $item = $this->service->update($id, $request->validated());
        return response()->json(new PhysicalInventoryResource($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
