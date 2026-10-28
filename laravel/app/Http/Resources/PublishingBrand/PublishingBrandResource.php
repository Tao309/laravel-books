<?php

namespace App\Http\Resources\PublishingBrand;

use App\Models\PublishingBrand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingBrandResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'name', type: 'string', example: 'Астрель СПб'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
class PublishingBrandResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            PublishingBrand::ID => $this->id,
            PublishingBrand::NAME => $this->name,
            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
