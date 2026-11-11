<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Services\LinkToProductService;
use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

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

    #[OA\Post(
        path: '/api/products/link_book/{product_id}/{book_id}',
        description: 'Link Book to Product',
        summary: 'Link Book to Product',
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
                parameter: 'book_id',
                name: 'book_id',
                description: 'Id of the Book',
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
                description: 'Link Book to Product',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
    public function __invoke(
        int $productId,
        int $bookId,
    ): JsonResponse {
        return $this->toJson([
            'success' => true,
            'data' => $this->linkProductService->linkBook($productId, $bookId),
        ]);

    }
}
