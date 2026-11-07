<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ProductUserDataRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductUserDataController extends ApiController
{
    public function __construct(
        private readonly ProductUserDataRepository $repo,
    ) {}

    #[OA\Get(
        path: '/api/product_user_data/{product_id}',
        description: 'Get ProductUserData by id',
        summary: 'Get ProductUserData by Product ID',
        tags: ['ProductUserData'],
        parameters: [
            new OA\Parameter(
                parameter: 'product_id',
                name: 'product_id',
                description: 'Id of the Product',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 2,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get ProductUserData by product_id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductUserDataResource'
                )
            ),
        ]
    )]
    public function show(int $productId): JsonResponse
    {
        return $this->toJson($this->repo->get($productId)->toResource());
    }
}
