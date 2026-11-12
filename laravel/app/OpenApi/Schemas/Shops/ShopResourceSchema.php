<?php

namespace App\OpenApi\Schemas\Shops;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: '1', property: 'id', type: 'integer'),
        new OA\Property(example: 'ozon', property: 'type', type: 'string'),
        new OA\Property(example: 'ozon.ru', property: 'domain', type: 'string'),
        new OA\Property(example: 'https://www.ozon.ru', property: 'url', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'updated_at', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'created_at', type: 'string'),
    ],
    schema: 'ShopResource',
    type: 'object'
)]
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            ref: Refs::SHOP_RESOURCE,
            type: 'object'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'ShopDataResource',
    type: 'object'
)]
class ShopResourceSchema {}
