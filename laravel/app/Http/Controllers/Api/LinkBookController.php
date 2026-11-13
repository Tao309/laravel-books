<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Services\Interfaces\ServiceInterface;
use App\Services\LinkToProductService;
use Illuminate\Http\JsonResponse;

class LinkBookController extends ApiController
{
    public function __construct(
        private readonly LinkToProductService $linkProductService,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function __invoke(
        int $productId,
        int $bookId,
    ): JsonResponse {
        return $this->toJson($this->linkProductService->linkBook($productId, $bookId));

    }
}
