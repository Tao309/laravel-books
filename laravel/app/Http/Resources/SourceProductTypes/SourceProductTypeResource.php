<?php

namespace App\Http\Resources\SourceProductTypes;

use App\Http\Resources\AbstractResource;
use App\Models\SourceProductType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductTypeResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'code', type: 'string', example: 'toys'),
        new OA\Property(property: 'name', type: 'string', example: 'Игрушки'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'SourceProductTypeDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/SourceProductTypeResource',
            type: 'object'
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class SourceProductTypeResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            SourceProductType::PARAM_ID => $this->offsetGet(SourceProductType::PARAM_ID),
            SourceProductType::PARAM_CODE => $this->offsetGet(SourceProductType::PARAM_CODE),
            SourceProductType::PARAM_NAME => $this->offsetGet(SourceProductType::PARAM_NAME),
            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
