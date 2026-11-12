<?php

namespace App\OpenApi\Schemas\Shops;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of Shops',
    items: new OA\Items(
        ref: Refs::SHOP_RESOURCE,
        type: 'object'
    ),
    schema: 'ShopsResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of Shops',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::SHOP_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'ShopsDataResourceCollection',
    type: 'object'
)]
class ShopsResourceCollectionSchema {}
