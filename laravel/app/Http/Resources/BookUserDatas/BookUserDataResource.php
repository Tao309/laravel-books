<?php

namespace App\Http\Resources\BookUserDatas;

use App\Http\Resources\AbstractResource;
use App\Models\BookUserData;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BookUserDataResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            BookUserData::PARAM_RELEASE_DATE => $this->offsetGet(BookUserData::PARAM_RELEASE_DATE)
                ? DateService::createFromDate($this->offsetGet(BookUserData::PARAM_RELEASE_DATE))
                : null,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => $this->offsetGet(BookUserData::PARAM_LISTEN_PRICE_VALUE),
            BookUserData::PARAM_COMMENT => $this->offsetGet(BookUserData::PARAM_COMMENT),

            Model::UPDATED_AT => DateService::createFromDate($this->offsetGet(Model::UPDATED_AT)),
            Model::CREATED_AT => DateService::createFromDate($this->offsetGet(Model::CREATED_AT)),
        ];
    }
}
