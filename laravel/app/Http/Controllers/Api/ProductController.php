<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexActionPaginate($request);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
