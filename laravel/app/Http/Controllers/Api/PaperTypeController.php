<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\PaperTypes\StorePaperTypeRequest;
use App\Http\Requests\PaperTypes\UpdatePaperTypeRequest;
use Illuminate\Http\JsonResponse;

class PaperTypeController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function store(StorePaperTypeRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    public function update(UpdatePaperTypeRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
