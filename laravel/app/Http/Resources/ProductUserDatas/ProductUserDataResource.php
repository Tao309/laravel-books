<?php

namespace App\Http\Resources\ProductUserDatas;

use App\Http\Resources\AbstractResource;
use App\Models\ProductUserData;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
class ProductUserDataResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            ProductUserData::PARAM_AVAILABLE => (bool) $this->offsetGet(ProductUserData::PARAM_AVAILABLE),
            ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => DateService::createFromDate(
                $this->offsetGet(ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM)
            ),
            ProductUserData::PARAM_AVAILABLE_DATE_FROM => DateService::createFromDate(
                $this->offsetGet(ProductUserData::PARAM_AVAILABLE_DATE_FROM)
            ),
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => $this->offsetGet(ProductUserData::PARAM_LISTEN_PRICE_VALUE),
            ProductUserData::PARAM_LISTEN_QTY_VALUE => $this->offsetGet(ProductUserData::PARAM_LISTEN_QTY_VALUE),
            ProductUserData::PARAM_RELEASE_DATE => DateService::createFromDate(
                $this->offsetGet(ProductUserData::PARAM_RELEASE_DATE)
            ),
            ProductUserData::PARAM_IS_ARCHIVE => (bool) $this->offsetGet(ProductUserData::PARAM_IS_ARCHIVE),
            ProductUserData::PARAM_COMMENT => $this->offsetGet(ProductUserData::PARAM_COMMENT),

            Model::UPDATED_AT => DateService::createFromDate($this->offsetGet(Model::UPDATED_AT)),
            Model::CREATED_AT => DateService::createFromDate($this->offsetGet(Model::CREATED_AT)),
        ];
    }
}
