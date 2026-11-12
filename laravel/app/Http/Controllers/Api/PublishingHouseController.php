<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\PublishingHouses\StorePublishingHouseRequest;
use App\Http\Requests\PublishingHouses\UpdatePublishingHouseRequest;
use Illuminate\Http\JsonResponse;

class PublishingHouseController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StorePublishingHouseRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdatePublishingHouseRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }
}
