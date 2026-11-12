<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BindingTypes\StoreBindingTypeRequest;
use App\Http\Requests\BindingTypes\UpdateBindingTypeRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\JsonResponse;

class BindingTypeController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StoreBindingTypeRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function update(UpdateBindingTypeRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
