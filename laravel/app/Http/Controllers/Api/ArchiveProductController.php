<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Products\ArchiveProductRequest;
use App\Http\Response\ResponseContext;
use App\Services\ArchiveProductService;
use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

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

    #[OA\Post(
        path: '/api/products/archive',
        description: 'Archive Product',
        summary: 'Archive to Product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/ArchiveProductRequest'
            )
        ),

        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Archive Product',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
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
