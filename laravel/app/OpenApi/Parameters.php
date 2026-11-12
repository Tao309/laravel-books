<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Components(
    parameters: [
        new OA\Parameter(
            description: 'Model id',
            in: 'path',
            name: 'id',
            parameter: 'Id',
            required: true,
            schema: new OA\Schema(
                example: 4,
                type: 'integer',
            )
        ),
        new OA\Parameter(
            description: 'Number of records to return',
            in: 'query',
            name: 'limit',
            parameter: 'Limit',
            required: false,
            schema: new OA\Schema(
                default: 20,
                maximum: 100,
                minimum: 1,
                type: 'integer'
            )
        ),
        new OA\Parameter(
            description: 'Search query',
            in: 'query',
            name: 'search',
            parameter: 'Search',
            required: false,
            schema: new OA\Schema(
                type: 'string'
            )
        ),
        new OA\Parameter(
            description: 'List of model IDs separated by commas',
            in: 'query',
            name: 'ids',
            parameter: 'Ids',
            required: false,
            schema: new OA\Schema(
                example: '1,2,3',
                type: 'string'
            )
        ),
        new OA\Parameter(
            description: 'Id of the Book',
            in: 'path',
            name: 'book_id',
            parameter: 'BookId',
            required: true,
            schema: new OA\Schema(
                example: 2,
                type: 'integer',
            )
        ),
        new OA\Parameter(
            description: 'Id of the Product',
            in: 'path',
            name: 'product_id',
            parameter: 'ProductId',
            required: true,
            schema: new OA\Schema(
                example: 2,
                type: 'integer',
            )
        ),
        new OA\Parameter(
            description: 'Id of the SourceProduct',
            in: 'path',
            name: 'source_product_id',
            parameter: 'SourceProductId',
            required: true,
            schema: new OA\Schema(
                example: 2,
                type: 'integer',
            )
        ),
    ]
)]
class Parameters {}
