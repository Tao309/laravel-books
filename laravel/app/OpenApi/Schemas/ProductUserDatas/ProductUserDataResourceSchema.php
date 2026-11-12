<?php

namespace App\OpenApi\Schemas\ProductUserDatas;

use App\Models\ProductUserData;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: true, property: ProductUserData::PARAM_AVAILABLE, type: 'boolean'),
        new OA\Property(example: '2026-09-10 10:10:19', property: ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM, type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: ProductUserData::PARAM_AVAILABLE_DATE_FROM, type: 'string'),
        new OA\Property(example: 500, property: ProductUserData::PARAM_LISTEN_PRICE_VALUE, type: 'integer'),
        new OA\Property(example: 10, property: ProductUserData::PARAM_LISTEN_QTY_VALUE, type: 'integer'),
        new OA\Property(example: '2026-09-10 10:10:19', property: ProductUserData::PARAM_RELEASE_DATE, type: 'string'),
        new OA\Property(example: false, property: ProductUserData::PARAM_IS_ARCHIVE, type: 'boolean'),
        new OA\Property(example: 'Ожидаем отзывы', property: ProductUserData::PARAM_COMMENT, type: 'text'),

        new OA\Property(example: '2026-09-10 10:10:19', property: Model::UPDATED_AT, type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: Model::CREATED_AT, type: 'string'),
    ],
    schema: 'ProductUserDataResource',
    type: 'object'
)]
class ProductUserDataResourceSchema {}
