<?php

namespace App\Http\Resources\SourceProducts;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Http\Resources\SourceProductUserDatas\SourceProductUserDataResource;
use App\Http\Resources\Users\UserResource;
use App\Models\SourceProduct;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
            ref: '#/components/schemas/SourceProductTypeResource',
            type: 'object'
        ),
        new OA\Property(
            property: SourceProduct::PARAM_USER_CREATED,
            ref: '#/components/schemas/UserResource',
            type: 'object'
        ),
        new OA\Property(
            property: SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA,
            ref: '#/components/schemas/SourceProductUserDataResource',
            type: 'object'
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'SourceProductDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/SourceProductResource',
            type: 'object'
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class SourceProductResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            SourceProduct::PARAM_ID => $this->offsetGet(SourceProduct::PARAM_ID),
            SourceProduct::PARAM_TITLE => $this->offsetGet(SourceProduct::PARAM_TITLE),
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),

            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => SourceProductTypeResource::make($this->sourceProductType),
            SourceProduct::PARAM_USER_CREATED => UserResource::make($this->userCreated),
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => SourceProductUserDataResource::make($this->sourceProductUserData),
        ];
    }
}
