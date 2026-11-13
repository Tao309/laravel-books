<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class SourceProductUserData
{
    #[OA\Get(
        path: '/api/source_product_user_data/{source_product_id}',
        operationId: 'source_product_user_data.show',
        description: 'Get SourceProduct by id',
        summary: 'Get SourceProduct by Book ID',
        tags: ['SourceProductUserData'],
        parameters: [
            new OA\Parameter(ref: Refs::SOURCE_PRODUCT_ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get SourceProduct by source_product_id',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_USER_DATA_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}
}
