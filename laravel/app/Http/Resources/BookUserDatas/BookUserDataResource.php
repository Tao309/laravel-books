<?php

namespace App\Http\Resources\BookUserDatas;

use App\Http\Resources\AbstractResource;
use App\Models\BookUserData;
use Carbon\Carbon;
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
                ? Carbon::createFromDate($this->offsetGet(Model::UPDATED_AT))->format('Y-m-d H:i:s')
                : null,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => $this->offsetGet(BookUserData::PARAM_LISTEN_PRICE_VALUE),
            BookUserData::PARAM_COMMENT => $this->offsetGet(BookUserData::PARAM_COMMENT),

            Model::UPDATED_AT => Carbon::createFromDate($this->offsetGet(Model::UPDATED_AT))->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->offsetGet(Model::CREATED_AT))->format('Y-m-d H:i:s'),
        ];
    }
}
