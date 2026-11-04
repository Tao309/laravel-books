<?php

namespace App\Http\Resources\SourceProductTypes;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductTypesResourceCollection',
    description: 'Collection of SourceProductTypes',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/SourceProductTypeResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'SourceProductTypesDataResourceCollection',
    description: 'Collection of SourceProductTypes',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/SourceProductTypeResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class SourceProductTypesResourceCollection extends AbstractResourceCollection
{
    public $collects = SourceProductTypeResource::class;
}
