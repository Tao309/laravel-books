<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Components(
    parameters: [
        new OA\Parameter(
            parameter: 'Limit',
            name: 'limit',
            description: 'Number of records to return',
            in: 'query',
            required: false,
            schema: new OA\Schema(
                type: 'integer',
                default: 20,
                maximum: 100,
                minimum: 1
            )
        ),
        new OA\Parameter(
            parameter: 'Search',
            name: 'search',
            description: 'Search query',
            in: 'query',
            required: false,
            schema: new OA\Schema(
                type: 'string'
            )
        ),
        new OA\Parameter(
            parameter: 'Ids',
            name: 'ids',
            description: 'List of model IDs separated by commas',
            in: 'query',
            required: false,
            schema: new OA\Schema(
                type: 'string',
                example: '1,2,3'
            )
        ),
    ]
)]
class Parameters {}
