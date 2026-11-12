<?php

namespace App\Http\Resources\Cities;

use App\Http\Resources\AbstractResource;
use App\Models\City;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CityResource extends AbstractResource
{
    /**
     * @return array<string, mixed>
     */
    public function getArrayData(Request $request): array
    {
        return [
            City::PARAM_ID => $this->id,
            City::PARAM_CODE => $this->code,
            City::PARAM_TITLE => $this->title,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
