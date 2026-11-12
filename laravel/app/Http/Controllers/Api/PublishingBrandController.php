<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\PublishingBrands\StorePublishingBrandRequest;
use App\Http\Requests\PublishingBrands\UpdatePublishingBrandRequest;
use Illuminate\Http\JsonResponse;

class PublishingBrandController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StorePublishingBrandRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdatePublishingBrandRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }
}
