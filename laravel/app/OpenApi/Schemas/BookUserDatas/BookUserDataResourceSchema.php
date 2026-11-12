<?php

namespace App\OpenApi\Schemas\BookUserDatas;

use App\Models\BookUserData;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: '2026-09-10 10:10:19', property: BookUserData::PARAM_RELEASE_DATE, type: 'string'),
        new OA\Property(example: 500, property: BookUserData::PARAM_LISTEN_PRICE_VALUE, type: 'integer'),
        new OA\Property(example: 'Ожидаем отзывы', property: BookUserData::PARAM_COMMENT, type: 'text'),

        new OA\Property(example: '2026-09-10 10:10:19', property: Model::UPDATED_AT, type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: Model::CREATED_AT, type: 'string'),
    ],
    schema: 'BookUserDataResource',
    type: 'object'
)]
class BookUserDataResourceSchema {}
