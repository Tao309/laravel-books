<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use Illuminate\Http\JsonResponse;

class ShopController extends ApiController
{
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }
}
