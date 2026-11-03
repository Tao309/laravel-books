<?php

namespace App\Http\Resources\PaperTypes;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaperTypesResourceCollection',
    description: 'Collection of PaperTypes',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/PaperTypeResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'PaperTypesDataResourceCollection',
    description: 'Collection of PaperTypes',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PaperTypeResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class PaperTypesResourceCollection extends AbstractResourceCollection
{
    public $collects = PaperTypeResource::class;
}
