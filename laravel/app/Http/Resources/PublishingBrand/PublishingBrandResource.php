<?php

namespace App\Http\Resources\PublishingBrand;

use App\Http\Resources\AbstractResource;
use App\Models\PublishingBrand;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PublishingBrandResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            PublishingBrand::PARAM_ID => $this->id,
            PublishingBrand::PARAM_NAME => $this->name,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
