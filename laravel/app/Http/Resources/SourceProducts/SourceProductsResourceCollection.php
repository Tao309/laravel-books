<?php

namespace App\Http\Resources\SourceProducts;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductsResourceCollection',
    description: 'Collection of SourceProducts',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/SourceProductResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'SourceProductsDataResourceCollection',
    description: 'Collection of SourceProducts',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/SourceProductResource',
                type: 'object'
            )
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class SourceProductsResourceCollection extends AbstractResourceCollection
{
    public $collects = SourceProductResource::class;
}
