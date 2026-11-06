<?php

namespace App\Http\Resources\BindingTypes;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BindingTypesCollection',
    description: 'Collection of binding types',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/BindingTypeResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BindingTypesDataResourceCollection',
    description: 'Collection of binding types',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/BindingTypeResource',
                type: 'object'
            )
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class BindingTypesResourceCollection extends AbstractResourceCollection
{
    public $collects = BindingTypeResource::class;
}
