<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Services\ServiceInterface;
use App\Services\UnlinkFromProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

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

    #[OA\Post(
        path: '/api/products/unlink/{product_id}',
        description: 'Unlink from Product',
        summary: 'Unlink from Product',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                parameter: 'product_id',
                name: 'product_id',
                description: 'Id of the Product',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Unlink from Product',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
    public function __invoke(
        int $productId,
    ): JsonResponse {
        return $this->toJson([
            'success' => true,
            'data' => $this->unlinkFromProductService->process($productId),
        ]);

    }
}
