<?php

namespace App\Http\Resources\PaperTypes;

use App\Http\Resources\AbstractResource;
use App\Models\PaperType;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaperTypeResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            PaperType::PARAM_ID => $this->offsetGet(PaperType::PARAM_ID),
            PaperType::PARAM_LABEL => $this->offsetGet(PaperType::PARAM_LABEL),
            PaperType::PARAM_DESCRIPTION => $this->offsetGet(PaperType::PARAM_DESCRIPTION),
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
