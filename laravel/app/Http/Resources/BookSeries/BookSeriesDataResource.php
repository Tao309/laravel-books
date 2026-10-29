<?php

namespace App\Http\Resources\BookSeries;

use App\Models\BookSeries;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookSeriesDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/BookSeriesResource',
            type: 'object'
        ),
    ],
    type: 'object'
)]
class BookSeriesDataResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                BookSeries::ID => $this->id,
                BookSeries::NAME => $this->name,
                Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
                Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
