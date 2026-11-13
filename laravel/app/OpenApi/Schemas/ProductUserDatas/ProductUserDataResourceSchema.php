<?php

namespace App\OpenApi\Schemas\ProductUserDatas;

use App\Models\ProductUserData;
use App\OpenApi\Refs;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductUserDataResource',
    properties: [
        new OA\Property(property: ProductUserData::PARAM_AVAILABLE, type: 'boolean', example: true),
        new OA\Property(property: ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: ProductUserData::PARAM_AVAILABLE_DATE_FROM, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: ProductUserData::PARAM_LISTEN_PRICE_VALUE, type: 'integer', example: 500),
        new OA\Property(property: ProductUserData::PARAM_LISTEN_QTY_VALUE, type: 'integer', example: 10),
        new OA\Property(property: ProductUserData::PARAM_RELEASE_DATE, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: ProductUserData::PARAM_IS_ARCHIVE, type: 'boolean', example: false),
        new OA\Property(property: ProductUserData::PARAM_COMMENT, type: 'text', example: 'Ожидаем отзывы'),

        new OA\Property(property: Model::UPDATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: Model::CREATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ProductUserDataResponse',
    description: 'ProductUserData response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::PRODUCT_USER_DATA_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class ProductUserDataResourceSchema {}
