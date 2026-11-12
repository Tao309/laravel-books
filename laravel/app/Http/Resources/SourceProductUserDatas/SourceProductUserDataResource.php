<?php

namespace App\Http\Resources\SourceProductUserDatas;

use App\Http\Resources\AbstractResource;
use App\Models\SourceProductUserData;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SourceProductUserDataResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => $this->offsetGet(SourceProductUserData::PARAM_LISTEN_PRICE_VALUE),
            SourceProductUserData::PARAM_COMMENT => $this->offsetGet(SourceProductUserData::PARAM_COMMENT),

            Model::UPDATED_AT => DateService::createFromDate($this->offsetGet(Model::UPDATED_AT)),
            Model::CREATED_AT => DateService::createFromDate($this->offsetGet(Model::CREATED_AT)),
        ];
    }
}
