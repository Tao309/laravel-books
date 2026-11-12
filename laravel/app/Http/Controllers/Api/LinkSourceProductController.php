<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Services\LinkToProductService;
use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;

class LinkSourceProductController extends ApiController
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
        int $sourceProductId,
    ): JsonResponse {
        return $this->toJson([
            'success' => true,
            'data' => $this->linkProductService->linkSourceProduct($productId, $sourceProductId),
        ]);

    }
}
