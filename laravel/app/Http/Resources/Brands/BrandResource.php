<?php

namespace App\Http\Resources\Brands;

use App\Http\Resources\AbstractResource;
use App\Models\Brand;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BrandResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            Brand::PARAM_ID => $this->id,
            Brand::PARAM_CODE => $this->code,
            Brand::PARAM_TITLE => $this->title,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
