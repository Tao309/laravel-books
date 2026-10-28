<?php

namespace App\Http\Resources\PublishingBrand;

use App\Models\PublishingBrand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingBrandDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/PublishingBrandResource',
            type: 'object'
        ),
    ],
    type: 'object'
)]
class PublishingBrandDataResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                PublishingBrand::ID => $this->id,
                PublishingBrand::NAME => $this->name,
                Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
                Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
