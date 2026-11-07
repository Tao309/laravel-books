<?php

namespace App\Http\Resources\Cities;

use App\Http\Resources\AbstractResource;
use App\Models\City;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CityResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'code', type: 'string', example: 'МОВ'),
        new OA\Property(property: 'title', type: 'string', example: 'Москва'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'CityDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/CityResource',
            type: 'object'
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class CityResource extends AbstractResource
{
    /**
     * @return array<string, mixed>
     */
    public function getArrayData(Request $request): array
    {
        return [
            City::PARAM_ID => $this->id,
            City::PARAM_CODE => $this->code,
            City::PARAM_TITLE => $this->title,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
