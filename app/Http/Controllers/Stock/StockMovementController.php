<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreStockMovementRequest;
use App\Http\Resources\Stock\StockMovementResource;
use App\Services\StockMovementService;
use Illuminate\Http\JsonResponse;

/**
 * Kardex — index, show and store only. No update or destroy.
 */
class StockMovementController extends Controller
{
    public function __construct(protected StockMovementService $service) {}

    public function index(): JsonResponse
    {
        return response()->json(StockMovementResource::collection($this->service->index()));
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(new StockMovementResource($this->service->show($id)));
    }

    public function store(StoreStockMovementRequest $request): JsonResponse
    {
        $item = $this->service->register($request->validated(), (int) auth('api')->id());
        return response()->json(new StockMovementResource($item), 201);
    }
}
