<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class SourceProductUserData
{
    #[OA\Get(
        description: 'Get SourceProduct by id',
        operationId: 'source_product_user_data.show',
        parameters: [
            new OA\Parameter(ref: Refs::SOURCE_PRODUCT_ID),
        ],
        path: '/api/source_product_user_data/{source_product_id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_USER_DATA_RESOURCE
                ),
                description: 'Get SourceProduct by source_product_id',
                response: 200
            ),
        ],
        summary: 'Get SourceProduct by Book ID',
        tags: ['SourceProductUserData']
    )]
    public function show(): void {}
}
