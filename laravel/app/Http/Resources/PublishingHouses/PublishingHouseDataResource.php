<?php

namespace App\Http\Resources\PublishingHouses;

use App\Models\PublishingHouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingHouseDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/PublishingHouseResource',
            type: 'object'
        ),
    ],
    type: 'object'
)]
class PublishingHouseDataResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                PublishingHouse::ID => $this->id,
                PublishingHouse::NAME => $this->name,
                Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
                Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
