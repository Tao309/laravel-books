<?php

namespace App\Http\Resources\PublishingHouses;

use App\Http\Resources\AbstractResource;
use App\Models\PublishingHouse;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingHouseResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'name', type: 'string', example: 'АСТ'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PublishingHouseDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/PublishingHouseResource',
            type: 'object'
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class PublishingHouseResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            PublishingHouse::PARAM_ID => $this->id,
            PublishingHouse::PARAM_NAME => $this->name,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
