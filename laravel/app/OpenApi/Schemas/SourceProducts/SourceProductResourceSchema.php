<?php

namespace App\OpenApi\Schemas\SourceProducts;

use App\Models\SourceProduct;
use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'title', type: 'string', example: 'Рубашка льняная с рукавами на лето'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
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
    description: 'Single SourceProduct response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::SOURCE_PRODUCT_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
#[OA\Schema(
    schema: 'SourceProductDestroyResponse',
    description: 'Delete SourceProduct response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'success', type: 'bool', example: true),
                    ],
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class SourceProductResourceSchema {}
