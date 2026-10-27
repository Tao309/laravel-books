<?php

namespace App\Http\Resources\Shops;

use App\Http\Resources\CommonResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopCollection',
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
    ],
    type: 'object'
)]
class ShopResourceCollection extends CommonResourceCollection {}
