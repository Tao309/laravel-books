<?php

namespace App\Http\Resources\SourceProducts;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Http\Resources\Users\UserResource;
use App\Models\SourceProduct;
use Carbon\Carbon;
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
            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),

            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => SourceProductTypeResource::make($this->sourceProductType),
            SourceProduct::PARAM_USER_CREATED => UserResource::make($this->userCreated),
        ];
    }
}
