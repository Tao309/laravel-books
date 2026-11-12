<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Cities\StoreCityRequest;
use App\Http\Requests\Cities\UpdateCityRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\JsonResponse;

class CityController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function update(UpdateCityRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
