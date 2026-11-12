<?php

namespace App\OpenApi\Schemas\SourceProducts;

use App\Models\SourceProduct;
use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductResource',
    properties: [
        new OA\Property(example: '1', property: 'id', type: 'integer'),
        new OA\Property(example: 'Рубашка льняная с рукавами на лето', property: 'title', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'updated_at', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'created_at', type: 'string'),
        new OA\Property(
            property: SourceProduct::PARAM_SOURCE_PRODUCT_TYPE,
            ref: Refs::SOURCE_PRODUCT_TYPE_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: SourceProduct::PARAM_USER_CREATED,
            ref: Refs::USER_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA,
            ref: Refs::SOURCE_PRODUCT_USER_DATA_RESOURCE,
            type: 'object'
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'SourceProductDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: Refs::SOURCE_PRODUCT_RESOURCE,
            type: 'object'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    type: 'object'
)]
class SourceProductResourceSchema {}
