<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\SourceProductTypes\StoreSourceProductTypeRequest;
use App\Http\Requests\SourceProductTypes\UpdateSourceProductTypeRequest;
use Illuminate\Http\JsonResponse;

class SourceProductTypeController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StoreSourceProductTypeRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdateSourceProductTypeRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
