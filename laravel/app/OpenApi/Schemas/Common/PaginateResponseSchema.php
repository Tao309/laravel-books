<?php

namespace App\OpenApi\Schemas\Common;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaginateResponse',
    description: 'Common PaginateResponse fields',
    properties: [
        new OA\Property(
            property: 'data',
            properties: [
                new OA\Property(
                    property: 'links',
                    properties: [
                        new OA\Property(property: 'first_page_url', type: 'string', example: 'http://localhost/api/models?page=1'),
                        new OA\Property(property: 'last_page_url', type: 'string', example: 'http://localhost/api/models?page=1'),
                        new OA\Property(property: 'next_page_url', type: 'string', example: 'http://localhost/api/models?page=2'),
                        new OA\Property(property: 'path', type: 'string', example: 'http://localhost/api/models'),
                        new OA\Property(property: 'prev_page_url', type: 'string', example: 'http://localhost/api/models?page=1'),
                    ],
                    type: 'object'
                ),
                new OA\Property(
                    property: 'meta',
                    properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(property: 'from', type: 'integer', example: 1),
                        new OA\Property(property: 'last_page', type: 'integer', example: 1),
                        new OA\Property(property: 'per_page', type: 'integer', example: 100),
                        new OA\Property(property: 'to', type: 'integer', example: 3),
                        new OA\Property(property: 'total', type: 'integer', example: 3),
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                    ],
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ],
    type: 'object',
)]
class PaginateResponseSchema {}
