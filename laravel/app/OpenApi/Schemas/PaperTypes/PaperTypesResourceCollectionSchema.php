<?php

namespace App\OpenApi\Schemas\PaperTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaperTypesResourceCollection',
    description: 'Collection of PaperTypes',
    type: 'object',
    items: new OA\Items(
        ref: Refs::PAPER_TYPE_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'PaperTypesDataResourceCollection',
    description: 'Collection of PaperTypes',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::PAPER_TYPE_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
class PaperTypesResourceCollectionSchema {}
