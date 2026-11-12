<?php

namespace App\Http\Resources\SourceProductTypes;

use App\Http\Resources\AbstractResource;
use App\Models\SourceProductType;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SourceProductTypeResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            SourceProductType::PARAM_ID => $this->offsetGet(SourceProductType::PARAM_ID),
            SourceProductType::PARAM_CODE => $this->offsetGet(SourceProductType::PARAM_CODE),
            SourceProductType::PARAM_NAME => $this->offsetGet(SourceProductType::PARAM_NAME),
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
