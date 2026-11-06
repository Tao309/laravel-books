<?php

namespace App\Http\Resources\Shops;

use App\Http\Resources\AbstractResource;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'type', type: 'string', example: 'ozon'),
        new OA\Property(property: 'domain', type: 'string', example: 'ozon.ru'),
        new OA\Property(property: 'url', type: 'string', example: 'https://www.ozon.ru'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ShopDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/ShopResource',
            type: 'object'
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class ShopResource extends AbstractResource
{
    /**
     * @return array<string, mixed>
     */
    public function getArrayData(Request $request): array
    {
        return [
            Shop::PARAM_ID => $this->id,
            Shop::PARAM_TYPE => $this->type,
            Shop::PARAM_DOMAIN => $this->domain,
            Shop::PARAM_URL => $this->url,
            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
