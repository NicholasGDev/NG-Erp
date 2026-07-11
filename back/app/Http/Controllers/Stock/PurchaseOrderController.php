<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StorePurchaseOrderRequest;
use App\Http\Requests\Stock\UpdatePurchaseOrderRequest;
use App\Http\Resources\Stock\PurchaseOrderResource;
use App\Services\PurchaseOrderService;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $service) {}

    public function index(): JsonResponse
    {
        return response()->json(PurchaseOrderResource::collection($this->service->index()));
    }

    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        $item = $this->service->store($request->validated());
        return response()->json(new PurchaseOrderResource($item), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(new PurchaseOrderResource($this->service->show($id)));
    }

    public function update(UpdatePurchaseOrderRequest $request, int $id): JsonResponse
    {
        $item = $this->service->update($id, $request->validated());
        return response()->json(new PurchaseOrderResource($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
