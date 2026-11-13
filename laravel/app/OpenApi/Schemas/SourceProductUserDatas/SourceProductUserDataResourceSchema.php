<?php

namespace App\OpenApi\Schemas\SourceProductUserDatas;

use App\Models\SourceProductUserData;
use App\OpenApi\Refs;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductUserDataResource',
    properties: [
        new OA\Property(property: SourceProductUserData::PARAM_LISTEN_PRICE_VALUE, type: 'integer', example: 500),
        new OA\Property(property: SourceProductUserData::PARAM_COMMENT, type: 'text', example: 'Ожидаем отзывы'),

        new OA\Property(property: Model::UPDATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: Model::CREATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
 )]
#[OA\Schema(
    schema: 'SourceProductUserDataResponse',
    description: 'SourceProductUserData response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::SOURCE_PRODUCT_USER_DATA_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class SourceProductUserDataResourceSchema {}
