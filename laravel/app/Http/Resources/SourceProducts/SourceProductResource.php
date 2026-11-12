<?php

namespace App\Http\Resources\SourceProducts;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Http\Resources\SourceProductUserDatas\SourceProductUserDataResource;
use App\Http\Resources\Users\UserResource;
use App\Models\SourceProduct;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SourceProductResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            SourceProduct::PARAM_ID => $this->offsetGet(SourceProduct::PARAM_ID),
            SourceProduct::PARAM_TITLE => $this->offsetGet(SourceProduct::PARAM_TITLE),
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),

            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => SourceProductTypeResource::make($this->sourceProductType),
            SourceProduct::PARAM_USER_CREATED => UserResource::make($this->userCreated),
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => SourceProductUserDataResource::make($this->sourceProductUserData),
        ];
    }
}
