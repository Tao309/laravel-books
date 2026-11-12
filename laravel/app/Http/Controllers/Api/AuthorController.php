<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Authors\StoreAuthorRequest;
use App\Http\Requests\Authors\UpdateAuthorRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\JsonResponse;

class AuthorController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexActionPaginate($request);
    }

    public function store(StoreAuthorRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdateAuthorRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
