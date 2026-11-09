<?php

namespace App\Http\Controllers\Api;

use App\Services\LinkToProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LinkSourceProductController extends ApiController
{
    public function __construct(
        private readonly LinkToProductService $linkProductService,
    ) {}

    #[OA\Post(
        path: '/api/products/link_source_product/{product_id}/{source_product_id}',
        description: 'Link SourceProduct to Product',
        summary: 'Link SourceProduct to Product',
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
            new OA\Parameter(
                parameter: 'source_product_id',
                name: 'source_product_id',
                description: 'Id of the SourceProduct',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 6,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Link SourceProduct to Product',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
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
