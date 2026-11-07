<?php

namespace App\Http\Resources\Authors;

use App\Http\Resources\AbstractResource;
use App\Models\Author;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthorResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'first_name', type: 'string', example: 'Фёдор'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Достоевский'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'AuthorDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/AuthorResource',
            type: 'object'
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class AuthorResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            Author::PARAM_ID => $this->id,
            Author::PARAM_FIRST_NAME => $this->first_name,
            Author::PARAM_LAST_NAME => $this->last_name,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
