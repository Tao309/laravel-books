<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\JsonResponse;

class BookController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexActionPaginate($request);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdateBookRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
