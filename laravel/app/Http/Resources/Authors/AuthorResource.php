<?php

namespace App\Http\Resources\Authors;

use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthorResourceData',
    properties: [
        new OA\Property(
            property: 'data',
            properties: [
                new OA\Property(property: 'first_name', type: 'string', example: 'Фёдор'),
                new OA\Property(property: 'last_name', type: 'string', example: 'Достоевский'),
                new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
                new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
            ],
            type: 'object'
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'AuthorResource',
    properties: [
        new OA\Property(property: 'first_name', type: 'string', example: 'Фёдор'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Достоевский'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
class AuthorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                Author::ID => $this->id,
                Author::FIRST_NAME => $this->first_name,
                Author::LAST_NAME => $this->last_name,
                Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
                Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
