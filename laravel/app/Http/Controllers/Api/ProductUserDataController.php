<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Repositories\ProductUserDataRepository;
use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;

class ProductUserDataController extends ApiController
{
    public function __construct(
        private readonly ProductUserDataRepository $repo,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function show(int $productId): JsonResponse
    {
        return $this->toJson($this->repo->get($productId)->toResource());
    }
}
