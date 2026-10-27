<?php

namespace App\Http\Resources\Cities;

use App\Models\City;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
class CityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            City::ID => $this->id,
            City::CODE => $this->code,
            City::TITLE => $this->title,
            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
