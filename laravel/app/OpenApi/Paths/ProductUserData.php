<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class ProductUserData
{
    #[OA\Get(
        path: '/api/product_user_data/{product_id}',
        operationId: 'product_user_data.show',
        description: 'Get ProductUserData by id',
        summary: 'Get ProductUserData by Product ID',
        tags: ['ProductUserData'],
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get ProductUserData by product_id',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_USER_DATA_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}
}
