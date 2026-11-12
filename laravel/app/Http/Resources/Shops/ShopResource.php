<?php

namespace App\Http\Resources\Shops;

use App\Http\Resources\AbstractResource;
use App\Models\Shop;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
