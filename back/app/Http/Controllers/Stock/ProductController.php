<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreProductRequest;
use App\Http\Requests\Stock\UpdateProductRequest;
use App\Http\Resources\Stock\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(ProductResource::collection($this->service->index((string) $request->query('search', ''))));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $item = $this->service->store($request->validated());
        return response()->json(new ProductResource($item), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(new ProductResource($this->service->show($id)));
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $item = $this->service->update($id, $request->validated());
        return response()->json(new ProductResource($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json(null, 204);
    }
}
