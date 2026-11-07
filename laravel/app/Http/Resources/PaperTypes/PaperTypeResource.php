<?php

namespace App\Http\Resources\PaperTypes;

use App\Http\Resources\AbstractResource;
use App\Models\PaperType;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaperTypeResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'label', type: 'string', example: 'Типографская'),
        new OA\Property(
            property: 'description',
            type: 'string',
            example: 'Бумага средней плотности, светло-серая, слегка шероховатая'
        ),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaperTypeDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/PaperTypeResource',
            type: 'object'
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class PaperTypeResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            PaperType::PARAM_ID => $this->offsetGet(PaperType::PARAM_ID),
            PaperType::PARAM_LABEL => $this->offsetGet(PaperType::PARAM_LABEL),
            PaperType::PARAM_DESCRIPTION => $this->offsetGet(PaperType::PARAM_DESCRIPTION),
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
