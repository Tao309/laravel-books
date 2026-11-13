<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Components(
    parameters: [
        new OA\Parameter(
            parameter: 'Id',
            name: 'id',
            description: 'Model id',
            in: 'path',
            required: true,
            schema: new OA\Schema(
                type: 'integer',
                example: 4,
            )
        ),
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
        new OA\Parameter(
            parameter: 'BookId',
            name: 'book_id',
            description: 'Id of the Book',
            in: 'path',
            required: true,
            schema: new OA\Schema(
                type: 'integer',
                example: 2,
            )
        ),
        new OA\Parameter(
            parameter: 'ProductId',
            name: 'product_id',
            description: 'Id of the Product',
            in: 'path',
            required: true,
            schema: new OA\Schema(
                type: 'integer',
                example: 2,
            )
        ),
        new OA\Parameter(
            parameter: 'SourceProductId',
            name: 'source_product_id',
            description: 'Id of the SourceProduct',
            in: 'path',
            required: true,
            schema: new OA\Schema(
                type: 'integer',
                example: 2,
            )
        ),
    ]
)]
class Parameters {}
