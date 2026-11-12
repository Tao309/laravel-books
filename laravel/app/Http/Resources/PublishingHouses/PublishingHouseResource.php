<?php

namespace App\Http\Resources\PublishingHouses;

use App\Http\Resources\AbstractResource;
use App\Models\PublishingHouse;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PublishingHouseResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            PublishingHouse::PARAM_ID => $this->id,
            PublishingHouse::PARAM_NAME => $this->name,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
