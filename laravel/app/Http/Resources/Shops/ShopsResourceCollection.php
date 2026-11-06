<?php

namespace App\Http\Resources\Shops;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopsResourceCollection',
    description: 'Collection of Shops',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/ShopResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'ShopsDataResourceCollection',
    description: 'Collection of Shops',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/ShopResource',
                type: 'object'
            )
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class ShopsResourceCollection extends AbstractResourceCollection {}
