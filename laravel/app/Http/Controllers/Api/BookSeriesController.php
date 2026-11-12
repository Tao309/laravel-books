<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BookSeries\StoreBookSeriesRequest;
use App\Http\Requests\BookSeries\UpdateBookSeriesRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\JsonResponse;

class BookSeriesController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StoreBookSeriesRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdateBookSeriesRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }
}
