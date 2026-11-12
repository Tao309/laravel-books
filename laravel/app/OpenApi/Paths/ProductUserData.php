<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class ProductUserData
{
    #[OA\Get(
        description: 'Get ProductUserData by id',
        operationId: 'product_user_data.show',
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
        ],
        path: '/api/product_user_data/{product_id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_USER_DATA_RESOURCE
                ),
                description: 'Get ProductUserData by product_id',
                response: 200
            ),
        ],
        summary: 'Get ProductUserData by Product ID',
        tags: ['ProductUserData']
    )]
    public function show(): void {}
}
