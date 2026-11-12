<?php

namespace App\Http\Resources\BookSeries;

use App\Http\Resources\AbstractResource;
use App\Models\BookSeries;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BookSeriesResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            BookSeries::PARAM_ID => $this->id,
            BookSeries::PARAM_NAME => $this->name,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
