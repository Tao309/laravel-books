<?php

namespace App\OpenApi\Schemas\PaperTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of PaperTypes',
    items: new OA\Items(
        ref: Refs::PAPER_TYPE_RESOURCE,
        type: 'object'
    ),
    schema: 'PaperTypesResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of PaperTypes',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::PAPER_TYPE_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'PaperTypesDataResourceCollection',
    type: 'object'
)]
class PaperTypesResourceCollectionSchema {}
