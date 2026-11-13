<?php

namespace App\OpenApi\Schemas\BookUserDatas;

use App\Models\BookUserData;
use App\OpenApi\Refs;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookUserDataResource',
    properties: [
        new OA\Property(property: BookUserData::PARAM_RELEASE_DATE, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: BookUserData::PARAM_LISTEN_PRICE_VALUE, type: 'integer', example: 500),
        new OA\Property(property: BookUserData::PARAM_COMMENT, type: 'text', example: 'Ожидаем отзывы'),

        new OA\Property(property: Model::UPDATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: Model::CREATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BookUserDataResponse',
    description: 'BookUserData response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::BOOK_USER_DATA_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BookUserDataResourceSchema {}
