<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Brands\StoreBrandRequest;
use App\Http\Requests\Brands\UpdateBrandRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\JsonResponse;

class BrandController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdateBrandRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
