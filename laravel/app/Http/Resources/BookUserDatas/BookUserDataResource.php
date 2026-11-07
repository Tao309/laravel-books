<?php

namespace App\Http\Resources\BookUserDatas;

use App\Http\Resources\AbstractResource;
use App\Models\BookUserData;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
class BookUserDataResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            BookUserData::PARAM_RELEASE_DATE => $this->offsetGet(BookUserData::PARAM_RELEASE_DATE)
                ? DateService::createFromDate($this->offsetGet(BookUserData::PARAM_RELEASE_DATE))
                : null,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => $this->offsetGet(BookUserData::PARAM_LISTEN_PRICE_VALUE),
            BookUserData::PARAM_COMMENT => $this->offsetGet(BookUserData::PARAM_COMMENT),

            Model::UPDATED_AT => DateService::createFromDate($this->offsetGet(Model::UPDATED_AT)),
            Model::CREATED_AT => DateService::createFromDate($this->offsetGet(Model::CREATED_AT)),
        ];
    }
}
