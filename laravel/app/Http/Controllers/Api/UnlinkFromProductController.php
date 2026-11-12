<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Services\ServiceInterface;
use App\Services\UnlinkFromProductService;
use Illuminate\Http\JsonResponse;

class UnlinkFromProductController extends ApiController
{
    public function __construct(
        private readonly UnlinkFromProductService $unlinkFromProductService,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function __invoke(
        int $productId,
    ): JsonResponse {
        return $this->toJson([
            'success' => true,
            'data' => $this->unlinkFromProductService->process($productId),
        ]);

    }
}
