<?php

namespace App\Http\Resources\BindingTypes;

use App\Http\Resources\AbstractResource;
use App\Models\BindingType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BindingTypeResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'label', type: 'string', example: 'Мягкий переплёт'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BindingTypeDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/BindingTypeResource',
            type: 'object'
        ),
    ],
    type: 'object'
)]
class BindingTypeResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            BindingType::PARAM_ID => $this->id,
            BindingType::PARAM_LABEL => $this->label,
            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
