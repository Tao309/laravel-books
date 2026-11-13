<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Products\ArchiveProductRequest;
use App\Http\Response\ResponseContext;
use App\Services\ArchiveProductService;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Http\JsonResponse;

class ArchiveProductController extends ApiController
{
    public function __construct(
        private readonly ArchiveProductService $archiveProductService,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function __invoke(ArchiveProductRequest $request): JsonResponse
    {
        $result = $this->archiveProductService->process($request->validated());

        if (! $result) {
            $this->context->setMessage('Product is not exists');
        }

        return $this->toJson(
            $result
                ?: [
                    ArchiveProductService::REQUIRE_TO_CREATE => true,
                ]
        );

    }
}
