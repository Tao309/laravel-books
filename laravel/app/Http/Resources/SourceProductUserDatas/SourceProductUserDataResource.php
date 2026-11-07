<?php

namespace App\Http\Resources\SourceProductUserDatas;

use App\Http\Resources\AbstractResource;
use App\Models\SourceProductUserData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
class SourceProductUserDataResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => $this->offsetGet(SourceProductUserData::PARAM_LISTEN_PRICE_VALUE),
            SourceProductUserData::PARAM_COMMENT => $this->offsetGet(SourceProductUserData::PARAM_COMMENT),

            Model::UPDATED_AT => Carbon::createFromDate($this->offsetGet(Model::UPDATED_AT))->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->offsetGet(Model::CREATED_AT))->format('Y-m-d H:i:s'),
        ];
    }
}
