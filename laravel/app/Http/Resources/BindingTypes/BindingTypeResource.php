<?php

namespace App\Http\Resources\BindingTypes;

use App\Http\Resources\AbstractResource;
use App\Models\BindingType;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BindingTypeResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            BindingType::PARAM_ID => $this->id,
            BindingType::PARAM_LABEL => $this->label,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
